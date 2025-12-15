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
        log_message('debug', "InventoryModel::updateQuantity called - Branch: {$branchId}, Product: {$productId}, Quantity: {$quantity}");
        
        $inventory = $this->where('branch_id', $branchId)
                         ->where('product_id', $productId)
                         ->first();
        
        if ($inventory) {
            log_message('debug', "Updating existing inventory ID: {$inventory['id']}");
            $result = $this->update($inventory['id'], [
                'quantity' => $quantity,
                'available_quantity' => $quantity - ($inventory['reserved_quantity'] ?? 0),
                'last_updated_by' => $userId,
                'last_updated_at' => date('Y-m-d H:i:s')
            ]);
            log_message('debug', "Update result: " . ($result ? 'Success' : 'Failed'));
            
            if (!$result) {
                log_message('error', "Failed to update inventory: " . json_encode($this->errors()));
            }
        } else {
            log_message('debug', "Creating new inventory record");
            $result = $this->insert([
                'branch_id' => $branchId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'reserved_quantity' => 0,
                'available_quantity' => $quantity,
                'last_updated_by' => $userId,
                'last_updated_at' => date('Y-m-d H:i:s')
            ]);
            log_message('debug', "Insert result: " . ($result ? "Success (ID: {$result})" : 'Failed'));
            
            if (!$result) {
                log_message('error', "Failed to insert inventory: " . json_encode($this->errors()));
            }
        }
        
        return $result ?? false;
    }
}

