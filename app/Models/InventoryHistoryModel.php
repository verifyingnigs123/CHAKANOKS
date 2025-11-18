<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryHistoryModel extends Model
{
    protected $table = 'inventory_history';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'branch_id', 'product_id', 'purchase_order_id', 'delivery_id', 
        'po_number', 'delivery_number', 'quantity_added', 'previous_quantity', 
        'new_quantity', 'transaction_type', 'received_by', 'notes', 'created_at'
    ];
    
    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    public function getHistoryByBranch($branchId, $limit = 50)
    {
        return $this->select('inventory_history.*, products.name as product_name, products.sku, branches.name as branch_name, users.full_name as received_by_name')
            ->join('products', 'products.id = inventory_history.product_id')
            ->join('branches', 'branches.id = inventory_history.branch_id')
            ->join('users', 'users.id = inventory_history.received_by', 'left')
            ->where('inventory_history.branch_id', $branchId)
            ->orderBy('inventory_history.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getHistoryByProduct($productId, $branchId = null, $limit = 50)
    {
        $builder = $this->select('inventory_history.*, products.name as product_name, products.sku, branches.name as branch_name, users.full_name as received_by_name')
            ->join('products', 'products.id = inventory_history.product_id')
            ->join('branches', 'branches.id = inventory_history.branch_id')
            ->join('users', 'users.id = inventory_history.received_by', 'left')
            ->where('inventory_history.product_id', $productId);
        
        if ($branchId) {
            $builder->where('inventory_history.branch_id', $branchId);
        }
        
        return $builder->orderBy('inventory_history.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getAllHistory($branchId = null, $limit = 100)
    {
        $builder = $this->select('inventory_history.*, products.name as product_name, products.sku, branches.name as branch_name, users.full_name as received_by_name')
            ->join('products', 'products.id = inventory_history.product_id')
            ->join('branches', 'branches.id = inventory_history.branch_id')
            ->join('users', 'users.id = inventory_history.received_by', 'left');
        
        if ($branchId) {
            $builder->where('inventory_history.branch_id', $branchId);
        }
        
        return $builder->orderBy('inventory_history.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}

