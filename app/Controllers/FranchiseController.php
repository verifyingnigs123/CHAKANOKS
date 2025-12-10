<?php

namespace App\Controllers;

use App\Models\FranchiseApplicationModel;
use App\Models\BranchModel;
use App\Models\ActivityLogModel;
use App\Libraries\NotificationService;

class FranchiseController extends BaseController
{
    protected $franchiseModel;
    protected $branchModel;
    protected $activityLogModel;
    protected $notificationService;

    public function __construct()
    {
        $this->franchiseModel = new FranchiseApplicationModel();
        $this->branchModel = new BranchModel();
        $this->activityLogModel = new ActivityLogModel();
        $this->notificationService = new NotificationService();
    }

    /**
     * List all franchise applications
     */
    public function applications()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if (!in_array($role, ['franchise_manager', 'central_admin'])) {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        $status = $this->request->getGet('status');
        
        try {
            $data['applications'] = $this->franchiseModel->getApplicationsWithDetails($status);
            $data['pending_count'] = $this->franchiseModel->getPendingCount();
        } catch (\Exception $e) {
            // If table doesn't exist or query fails, return empty data
            $data['applications'] = [];
            $data['pending_count'] = 0;
        }
        
        $data['role'] = $role;
        $data['current_status'] = $status;

        return view('franchise/applications', $data);
    }

    /**
     * View single application
     */
    public function viewApplication($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if (!in_array($role, ['franchise_manager', 'central_admin'])) {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        $application = $this->franchiseModel->select('franchise_applications.*, users.full_name as reviewed_by_name, approver.full_name as approved_by_name, branches.name as branch_name')
            ->join('users', 'users.id = franchise_applications.reviewed_by', 'left')
            ->join('users as approver', 'approver.id = franchise_applications.approved_by', 'left')
            ->join('branches', 'branches.id = franchise_applications.branch_id', 'left')
            ->find($id);

        if (!$application) {
            return redirect()->to('/franchise/applications')->with('error', 'Application not found');
        }

        $data['application'] = $application;
        $data['role'] = $role;
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();

        return view('franchise/view_application', $data);
    }

    /**
     * Mark application as under review
     */
    public function startReview($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if (!in_array($role, ['franchise_manager', 'central_admin'])) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $application = $this->franchiseModel->find($id);
        if (!$application) {
            return redirect()->back()->with('error', 'Application not found');
        }

        $this->franchiseModel->update($id, [
            'status' => 'under_review',
            'reviewed_by' => $session->get('user_id'),
            'reviewed_at' => date('Y-m-d H:i:s'),
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'review', 'franchise_application', "Started review of application: {$application['application_number']}");

        return redirect()->back()->with('success', 'Application marked as under review');
    }

    /**
     * Approve application
     */
    public function approve($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if (!in_array($role, ['franchise_manager', 'central_admin'])) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $application = $this->franchiseModel->find($id);
        if (!$application) {
            return redirect()->back()->with('error', 'Application not found');
        }

        $reviewNotes = $this->request->getPost('review_notes');

        $this->franchiseModel->update($id, [
            'status' => 'approved',
            'approved_by' => $session->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s'),
            'review_notes' => $reviewNotes,
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'approve', 'franchise_application', "Approved application: {$application['application_number']}");

        // Notify central admin
        $this->notificationService->sendToRole('central_admin', 'success', 'Franchise Application Approved', "Application {$application['application_number']} has been approved.", base_url("franchise/applications/view/{$id}"));

        return redirect()->back()->with('success', 'Application approved successfully');
    }

    /**
     * Reject application
     */
    public function reject($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if (!in_array($role, ['franchise_manager', 'central_admin'])) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $application = $this->franchiseModel->find($id);
        if (!$application) {
            return redirect()->back()->with('error', 'Application not found');
        }

        $reviewNotes = $this->request->getPost('review_notes');

        $this->franchiseModel->update($id, [
            'status' => 'rejected',
            'reviewed_by' => $session->get('user_id'),
            'reviewed_at' => date('Y-m-d H:i:s'),
            'review_notes' => $reviewNotes,
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'reject', 'franchise_application', "Rejected application: {$application['application_number']}");

        return redirect()->back()->with('success', 'Application rejected');
    }

    /**
     * Convert approved application to branch/partner
     */
    public function convertToBranch($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if (!in_array($role, ['franchise_manager', 'central_admin'])) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $application = $this->franchiseModel->find($id);
        if (!$application || $application['status'] !== 'approved') {
            return redirect()->back()->with('error', 'Application not found or not approved');
        }

        // Create new branch from application
        $branchCode = 'FR-' . strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $application['city']), 0, 3)) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        $branchData = [
            'name' => $application['business_name'] ?: $application['applicant_name'] . ' Franchise',
            'code' => $branchCode,
            'address' => $application['proposed_location'],
            'city' => $application['city'],
            'phone' => $application['phone'],
            'email' => $application['email'],
            'manager_name' => $application['applicant_name'],
            'type' => 'franchise',
            'status' => 'active',
        ];

        $branchId = $this->branchModel->insert($branchData);

        if ($branchId) {
            // Update application
            $this->franchiseModel->update($id, [
                'status' => 'converted',
                'branch_id' => $branchId,
            ]);

            $this->activityLogModel->logActivity($session->get('user_id'), 'convert', 'franchise_application', "Converted application {$application['application_number']} to branch: {$branchCode}");

            return redirect()->to('/branches/view/' . $branchId)->with('success', 'Application converted to franchise branch successfully');
        }

        return redirect()->back()->with('error', 'Failed to create branch');
    }

    /**
     * Get applications data for real-time updates (AJAX)
     */
    public function getApplicationsData()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $role = $session->get('role');
        if (!in_array($role, ['franchise_manager', 'central_admin'])) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(403);
        }

        $status = $this->request->getGet('status');
        $applications = $this->franchiseModel->getApplicationsWithDetails($status);
        $pendingCount = $this->franchiseModel->getPendingCount();

        return $this->response->setJSON([
            'success' => true,
            'applications' => $applications,
            'pending_count' => $pendingCount
        ]);
    }

    /**
     * Franchise partners list (approved/converted applications)
     */
    public function partners()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if (!in_array($role, ['franchise_manager', 'central_admin'])) {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        // Get franchise branches
        $data['partners'] = $this->branchModel->where('type', 'franchise')
            ->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->findAll();
        $data['role'] = $role;

        return view('franchise/partners', $data);
    }
}
