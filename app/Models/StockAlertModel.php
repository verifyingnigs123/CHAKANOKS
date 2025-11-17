<?php

namespace App\Models;

use CodeIgniter\Model;

class StockAlertModel extends Model
{
    protected $table = 'stock_alerts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'branch_id', 'product_id', 'alert_type', 'current_quantity', 'threshold', 'expiry_date', 
        'status', 'acknowledged_by', 'acknowledged_at', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'branch_id' => 'required|integer',
        'product_id' => 'required|integer',
        'alert_type' => 'required|in_list[low_stock,out_of_stock,expiring_soon,expired]',
    ];
}

