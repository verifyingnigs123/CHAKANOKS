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
    
    // Disable validation rules - handle manually in controller if needed
    protected $validationRules = [];
}

