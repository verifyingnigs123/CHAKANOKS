<?php

namespace App\Models;

use CodeIgniter\Model;

class BranchModel extends Model
{
    protected $table = 'branches';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'code', 'address', 'city', 'phone', 'email', 'manager_id', 'manager_name', 'status', 'type', 'is_franchise', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    
    // Disable validation rules - handle manually in controller if needed
    protected $validationRules = [];
}

