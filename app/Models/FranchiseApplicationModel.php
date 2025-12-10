<?php

namespace App\Models;

use CodeIgniter\Model;

class FranchiseApplicationModel extends Model
{
    protected $table = 'franchise_applications';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'application_number',
        'applicant_name',
        'email',
        'phone',
        'business_name',
        'proposed_location',
        'city',
        'province',
        'investment_capital',
        'business_experience',
        'motivation',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'approved_by',
        'approved_at',
        'branch_id',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'applicant_name' => 'required|min_length[2]|max_length[150]',
        'email' => 'required|valid_email',
        'phone' => 'required|min_length[10]',
        'proposed_location' => 'required',
        'city' => 'required',
        'province' => 'required',
    ];

    /**
     * Generate unique application number
     */
    public function generateApplicationNumber()
    {
        $prefix = 'FA-' . date('Ym') . '-';
        $lastApp = $this->like('application_number', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastApp) {
            $lastNumber = (int) substr($lastApp['application_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get applications with reviewer info
     */
    public function getApplicationsWithDetails($status = null)
    {
        $builder = $this->select('franchise_applications.*, users.full_name as reviewed_by_name, approver.full_name as approved_by_name')
            ->join('users', 'users.id = franchise_applications.reviewed_by', 'left')
            ->join('users as approver', 'approver.id = franchise_applications.approved_by', 'left')
            ->orderBy('franchise_applications.created_at', 'DESC');

        if ($status) {
            $builder->where('franchise_applications.status', $status);
        }

        return $builder->findAll();
    }

    /**
     * Get pending applications count
     */
    public function getPendingCount()
    {
        return $this->where('status', 'pending')->countAllResults();
    }
}
