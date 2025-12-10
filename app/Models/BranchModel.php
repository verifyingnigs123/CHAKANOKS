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
    
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[150]',
        'code' => 'required|min_length[2]|max_length[50]|is_unique[branches.code,id,{id}]',
    ];
}

