<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderItemModel extends Model
{
    protected $table = 'purchase_order_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'purchase_order_id', 'product_id', 'supplier_product_id', 
        'product_name', 'product_sku', 'product_unit',
        'quantity', 'quantity_received', 'unit_price', 'total_price', 'created_at'
    ];
    
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    
    /**
     * Get items by purchase order ID with product details
     * Now supports both system products and supplier products
     * Returns consistent field names: product_name, sku, unit
     */
    public function getByPurchaseOrder($purchaseOrderId)
    {
        $items = $this->where('purchase_order_id', $purchaseOrderId)->findAll();
        
        // Normalize field names for view compatibility
        foreach ($items as &$item) {
            if (empty($item['product_name']) && !empty($item['product_id'])) {
                // Legacy item - get from products table
                $productModel = new ProductModel();
                $product = $productModel->find($item['product_id']);
                if ($product) {
                    $item['product_name'] = $product['name'];
                    $item['sku'] = $product['sku'];
                    $item['unit'] = $product['unit'];
                }
            } else {
                // New supplier product item - map field names for view compatibility
                $item['sku'] = $item['product_sku'] ?? '';
                $item['unit'] = $item['product_unit'] ?? 'pcs';
            }
        }
        
        return $items;
    }
}

