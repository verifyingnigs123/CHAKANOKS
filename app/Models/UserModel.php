<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'username', 'password', 'full_name', 'email', 'phone', 'role', 'branch_id', 'supplier_id', 'status', 'last_login', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
}
