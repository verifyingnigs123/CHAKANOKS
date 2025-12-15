<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierProductModel extends Model
{
    protected $table = 'supplier_products';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'supplier_id', 'created_by', 'product_id', 'name', 'sku', 'description', 'unit', 'category',
        'supplier_sku', 'supplier_price', 'stock_quantity', 'min_order_qty', 'lead_time_days', 
        'is_preferred', 'status', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    
    /**
     * Get all products for a specific supplier (supplier's own products)
     * Now filters by created_by (user_id) to separate products per supplier user
     */
    public function getProductsBySupplier($supplierId, $userId = null)
    {
        $builder = $this->where('supplier_id', $supplierId)
            ->where('status', 'active');
        
        // If userId is provided, filter by created_by for user-specific products
        if ($userId) {
            $builder->where('created_by', $userId);
        }
        
        return $builder->orderBy('name', 'ASC')->findAll();
    }
    
    /**
     * Get products by user (for supplier users to see only their own products)
     * Also includes legacy products where created_by is null but supplier_id matches
     */
    public function getProductsByUser($userId)
    {
        // First get the supplier_id for this user
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $supplierId = $user['supplier_id'] ?? null;
        
        $builder = $this->where('status', 'active');
        
        if ($supplierId) {
            // Get products created by this user OR legacy products for this supplier
            // Use supplier_id as the main filter since products belong to the supplier company
            $builder->where('supplier_id', $supplierId);
        } else {
            // Fallback to just created_by if no supplier_id
            $builder->where('created_by', $userId);
        }
        
        return $builder->orderBy('name', 'ASC')->findAll();
    }
    
    /**
     * Get all suppliers for a specific product
     */
    public function getSuppliersByProduct($productId)
    {
        return $this->select('supplier_products.*, suppliers.name as supplier_name, suppliers.code as supplier_code')
            ->join('suppliers', 'suppliers.id = supplier_products.supplier_id')
            ->where('supplier_products.product_id', $productId)
            ->where('supplier_products.status', 'active')
            ->findAll();
    }
    
    /**
     * Check if user has a product with same SKU
     * Now checks by user_id instead of supplier_id for user-specific SKU uniqueness
     */
    public function supplierHasProductSku($supplierId, $sku, $excludeId = null, $userId = null)
    {
        $builder = $this->where('supplier_id', $supplierId)
            ->where('sku', $sku)
            ->where('status', 'active');
        
        // If userId provided, check SKU uniqueness per user
        if ($userId) {
            $builder->where('created_by', $userId);
        }
            
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->first() !== null;
    }
    
    /**
     * Create supplier's own product
     * Now includes created_by to track which user created the product
     */
    public function createSupplierProduct($supplierId, $data, $userId = null)
    {
        return $this->insert([
            'supplier_id' => $supplierId,
            'created_by' => $userId, // Track which user created this product
            'product_id' => null, // Not linked to system products
            'name' => $data['name'],
            'sku' => $data['sku'],
            'description' => $data['description'] ?? null,
            'unit' => $data['unit'] ?? 'pcs',
            'category' => $data['category'] ?? null,
            'supplier_price' => $data['supplier_price'] ?? 0,
            'stock_quantity' => $data['stock_quantity'] ?? 0,
            'min_order_qty' => $data['min_order_qty'] ?? 1,
            'lead_time_days' => $data['lead_time_days'] ?? null,
            'status' => 'active',
        ]);
    }
    
    /**
     * Update supplier product
     */
    public function updateSupplierProduct($id, $data)
    {
        return $this->update($id, [
            'name' => $data['name'],
            'sku' => $data['sku'],
            'description' => $data['description'] ?? null,
            'unit' => $data['unit'] ?? 'pcs',
            'category' => $data['category'] ?? null,
            'supplier_price' => $data['supplier_price'] ?? 0,
            'stock_quantity' => $data['stock_quantity'] ?? 0,
            'min_order_qty' => $data['min_order_qty'] ?? 1,
            'lead_time_days' => $data['lead_time_days'] ?? null,
        ]);
    }
    
    /**
     * Remove product (soft delete)
     */
    public function removeProduct($id)
    {
        return $this->update($id, ['status' => 'inactive']);
    }
    
    /**
     * Generate unique SKU for supplier
     */
    public function generateSku($supplierId, $prefix = 'SKU')
    {
        // Generate unique SKU with timestamp and random string
        $timestamp = strtoupper(base_convert(time(), 10, 36));
        $random = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
        $sku = $prefix . '-' . substr($timestamp, -4) . $random;
        
        // Ensure uniqueness
        $counter = 0;
        $originalSku = $sku;
        while ($this->where('supplier_id', $supplierId)->where('sku', $sku)->first()) {
            $counter++;
            $sku = $originalSku . $counter;
        }
        
        return $sku;
    }
    
    /**
     * Get system products not yet assigned to this supplier (legacy method)
     * Returns empty array since suppliers now create their own products
     */
    public function getUnassignedProducts($supplierId)
    {
        // Legacy method - suppliers now create their own products
        // Return empty array to avoid errors in old views
        return [];
    }
    
    /**
     * Add system product to supplier catalog (legacy method)
     */
    public function addProductToSupplier($supplierId, $productId, $data = [])
    {
        // Check if already exists
        $existing = $this->where('supplier_id', $supplierId)
            ->where('product_id', $productId)
            ->first();
            
        if ($existing) {
            return false;
        }
        
        // Get product details
        $productModel = new ProductModel();
        $product = $productModel->find($productId);
        
        return $this->insert([
            'supplier_id' => $supplierId,
            'product_id' => $productId,
            'name' => $product ? $product['name'] : 'Unknown',
            'sku' => $product ? $product['sku'] : '',
            'unit' => $product ? ($product['unit'] ?? 'pcs') : 'pcs',
            'supplier_price' => $data['supplier_price'] ?? ($product ? $product['cost_price'] : 0),
            'min_order_qty' => $data['min_order_qty'] ?? 1,
            'lead_time_days' => $data['lead_time_days'] ?? null,
            'status' => 'active',
        ]);
    }
}
