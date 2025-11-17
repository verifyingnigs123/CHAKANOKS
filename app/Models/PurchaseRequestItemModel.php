<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseRequestItemModel extends Model
{
    protected $table = 'purchase_request_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'purchase_request_id', 'product_id', 'quantity', 'unit_price', 'total_price', 'notes', 'created_at'
    ];
    
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
}

