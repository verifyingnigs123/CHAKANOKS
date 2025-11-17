<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryAdjustmentModel extends Model
{
    protected $table = 'inventory_adjustments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'product_id', 'branch_id', 'type', 'old_quantity', 'new_quantity', 
        'quantity_change', 'reason', 'adjusted_by', 'created_at'
    ];
    
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
}

