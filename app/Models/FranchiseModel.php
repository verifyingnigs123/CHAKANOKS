<?php

namespace App\Models;

use CodeIgniter\Model;

class FranchiseModel extends Model
{
    protected $table = 'franchises';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'branch_id', 'franchise_name', 'owner_name', 'contact_email', 'contact_phone', 
        'agreement_date', 'expiry_date', 'royalty_percentage', 'status', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'branch_id' => 'required|integer|is_unique[franchises.branch_id,id,{id}]',
        'franchise_name' => 'required|min_length[3]|max_length[150]',
        'owner_name' => 'required|min_length[3]|max_length[150]',
    ];
}

