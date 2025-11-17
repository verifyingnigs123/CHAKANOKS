<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'sku', 'barcode', 'description', 'category', 'unit', 'is_perishable', 'shelf_life_days', 
        'min_stock_level', 'max_stock_level', 'cost_price', 'selling_price', 'status', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[200]',
        'sku' => 'required|min_length[3]|max_length[100]|is_unique[products.sku,id,{id}]',
        'barcode' => 'permit_empty|max_length[100]|is_unique[products.barcode,id,{id}]',
    ];
}

