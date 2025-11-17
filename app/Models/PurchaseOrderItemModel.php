<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderItemModel extends Model
{
    protected $table = 'purchase_order_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'purchase_order_id', 'product_id', 'quantity', 'quantity_received', 'unit_price', 'total_price', 'created_at'
    ];
    
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
}

