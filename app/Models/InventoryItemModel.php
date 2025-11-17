<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryItemModel extends Model
{
    protected $table = 'inventory_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'inventory_id', 'batch_number', 'expiry_date', 'quantity', 'cost_price', 
        'received_date', 'status', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'inventory_id' => 'required|integer',
        'quantity' => 'required|integer|greater_than[0]',
    ];
}

