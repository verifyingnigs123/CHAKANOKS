<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'branch_id', 'product_id', 'quantity', 'reserved_quantity', 'available_quantity', 
        'last_updated_by', 'last_updated_at', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'branch_id' => 'required|integer',
        'product_id' => 'required|integer',
        'quantity' => 'required|integer|greater_than_equal_to[0]',
    ];
    
    public function updateQuantity($branchId, $productId, $quantity, $userId = null)
    {
        $inventory = $this->where('branch_id', $branchId)
                         ->where('product_id', $productId)
                         ->first();
        
        if ($inventory) {
            $this->update($inventory['id'], [
                'quantity' => $quantity,
                'available_quantity' => $quantity - $inventory['reserved_quantity'],
                'last_updated_by' => $userId,
                'last_updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->insert([
                'branch_id' => $branchId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'reserved_quantity' => 0,
                'available_quantity' => $quantity,
                'last_updated_by' => $userId,
                'last_updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}

