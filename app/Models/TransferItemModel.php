<?php

namespace App\Models;

use CodeIgniter\Model;

class TransferItemModel extends Model
{
    protected $table = 'transfer_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transfer_id', 'product_id', 'quantity', 'quantity_received', 'created_at'
    ];
    
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
}

