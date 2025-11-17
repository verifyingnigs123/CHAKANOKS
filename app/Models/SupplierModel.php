<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'code', 'contact_person', 'email', 'phone', 'address', 'payment_terms', 'delivery_terms', 'rating', 'status', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[150]',
        'code' => 'required|min_length[2]|max_length[50]|is_unique[suppliers.code,id,{id}]',
        'email' => 'permit_empty|valid_email|max_length[150]',
    ];
}

