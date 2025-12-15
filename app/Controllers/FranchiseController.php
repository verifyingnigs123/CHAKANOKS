<?php

namespace App\Controllers;

use App\Models\FranchiseApplicationModel;
use App\Models\BranchModel;
use App\Models\UserModel;
use App\Models\ActivityLogModel;
use App\Libraries\NotificationService;

class FranchiseController extends BaseController
{
    protected $franchiseModel;
    protected $branchModel;
    protected $userModel;
    protected $activityLogModel;
    protected $notificationService;

    public function __construct()
    {
        $this->franchiseModel = new FranchiseApplicationModel();
        $this->branchModel = new BranchModel();
        $this->userModel = new UserModel();
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

        // Send email notification to applicant
        $this->sendEmailNotification($application, 'approved', $reviewNotes);

        return redirect()->back()->with('success', 'Application approved successfully. Email notification sent to applicant.');
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

        // Send email notification to applicant
        $this->sendEmailNotification($application, 'rejected', $reviewNotes);

        return redirect()->back()->with('success', 'Application rejected. Email notification sent to applicant.');
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
            // Generate random password for the new user
            $generatedPassword = $this->generateRandomPassword();
            
            // Create Branch Manager user account
            $userData = [
                'full_name' => $application['applicant_name'],
                'email' => $application['email'],
                'password' => password_hash($generatedPassword, PASSWORD_DEFAULT),
                'role' => 'branch_manager',
                'branch_id' => $branchId,
                'status' => 'active',
            ];
            
            $userId = $this->userModel->insert($userData);
            
            // Update application
            $this->franchiseModel->update($id, [
                'status' => 'converted',
                'branch_id' => $branchId,
            ]);

            $this->activityLogModel->logActivity($session->get('user_id'), 'convert', 'franchise_application', "Converted application {$application['application_number']} to branch: {$branchCode} with user account");

            // Send email with login credentials
            $branchName = $branchData['name'];
            $this->sendBranchCreatedEmail($application, $branchName, $branchCode, $generatedPassword);

            return redirect()->to('/branches/view/' . $branchId)->with('success', 'Branch and user account created successfully! Login credentials sent to applicant\'s email.');
        }

        return redirect()->back()->with('error', 'Failed to create branch');
    }
    
    /**
     * Generate random password
     */
    private function generateRandomPassword($length = 10)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $password;
    }
    
    /**
     * Send email with branch and login credentials
     */
    private function sendBranchCreatedEmail($application, $branchName, $branchCode, $password)
    {
        try {
            // Initialize email with config
            $emailConfig = new \Config\Email();
            $email = \Config\Services::email($emailConfig);
            
            // Clear any previous state
            $email->clear();
            
            // Set from using config values
            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
            $email->setTo($application['email']);
            $email->setSubject('Your Franchise Branch is Ready! - ChakaNoks SCMS');
            
            log_message('info', "Attempting to send branch created email to: " . $application['email'] . " from: " . $emailConfig->fromEmail);
            
            $loginUrl = base_url('login');
            
            $message = "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                    <div style='background: linear-gradient(135deg, #8b5cf6, #6366f1); padding: 20px; border-radius: 10px 10px 0 0;'>
                        <h1 style='color: white; margin: 0;'>🏪 Your Branch is Ready!</h1>
                    </div>
                    <div style='background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;'>
                        <p>Dear <strong>{$application['applicant_name']}</strong>,</p>
                        <p>Great news! Your franchise branch has been created and is now ready for operation.</p>
                        
                        <div style='background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #8b5cf6;'>
                            <h3 style='margin: 0 0 15px 0; color: #8b5cf6;'>Branch Information</h3>
                            <p style='margin: 5px 0;'><strong>Branch Name:</strong> {$branchName}</p>
                            <p style='margin: 5px 0;'><strong>Branch Code:</strong> {$branchCode}</p>
                            <p style='margin: 5px 0;'><strong>Location:</strong> {$application['proposed_location']}, {$application['city']}</p>
                        </div>
                        
                        <div style='background: #ecfdf5; padding: 20px; border-radius: 8px; margin: 20px 0; border: 2px solid #10b981;'>
                            <h3 style='margin: 0 0 15px 0; color: #059669;'>🔐 Your Login Credentials</h3>
                            <p style='margin: 5px 0;'><strong>Email:</strong> {$application['email']}</p>
                            <p style='margin: 5px 0;'><strong>Password:</strong> <code style='background: #d1fae5; padding: 2px 8px; border-radius: 4px; font-size: 14px;'>{$password}</code></p>
                            <p style='margin: 15px 0 0 0; font-size: 12px; color: #6b7280;'>⚠️ Please change your password after your first login for security.</p>
                        </div>
                        
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='{$loginUrl}' style='display: inline-block; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 15px 40px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;'>
                                Login to Your Dashboard
                            </a>
                        </div>
                        
                        <p>As a Branch Manager, you can:</p>
                        <ul style='color: #4b5563;'>
                            <li>View and manage your branch inventory</li>
                            <li>Create purchase requests for supplies</li>
                            <li>Receive deliveries</li>
                            <li>Manage branch transfers</li>
                        </ul>
                        
                        <p style='margin-top: 30px;'>Welcome to the ChakaNoks family!</p>
                        <p>Best regards,<br><strong>ChakaNoks SCMS Team</strong></p>
                    </div>
                </div>
            </body>
            </html>";
            
            $email->setMessage($message);
            $email->setMailType('html');
            
            if ($email->send()) {
                log_message('info', "Successfully sent branch created email to: " . $application['email']);
            } else {
                log_message('error', 'Failed to send branch created email: ' . $email->printDebugger(['headers', 'subject', 'body']));
            }
        } catch (\Exception $e) {
            log_message('error', 'Branch created email error: ' . $e->getMessage());
        }
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
     * Send email notification to applicant
     */
    private function sendEmailNotification($application, $status, $notes = '')
    {
        try {
            // Initialize email with config
            $emailConfig = new \Config\Email();
            $email = \Config\Services::email($emailConfig);
            
            // Clear any previous state
            $email->clear();
            
            // Set from using config values
            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
            $email->setTo($application['email']);
            
            log_message('info', "Attempting to send {$status} email to: " . $application['email'] . " from: " . $emailConfig->fromEmail);
            
            if ($status === 'approved') {
                $email->setSubject('Congratulations! Your Franchise Application Has Been Approved - ChakaNoks SCMS');
                $message = "
                <html>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <div style='background: linear-gradient(135deg, #10b981, #059669); padding: 20px; border-radius: 10px 10px 0 0;'>
                            <h1 style='color: white; margin: 0;'>🎉 Application Approved!</h1>
                        </div>
                        <div style='background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;'>
                            <p>Dear <strong>{$application['applicant_name']}</strong>,</p>
                            <p>We are pleased to inform you that your franchise application has been <strong style='color: #10b981;'>APPROVED</strong>!</p>
                            
                            <div style='background: white; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #10b981;'>
                                <p style='margin: 0;'><strong>Application Number:</strong> {$application['application_number']}</p>
                                <p style='margin: 5px 0 0 0;'><strong>Proposed Location:</strong> {$application['proposed_location']}, {$application['city']}</p>
                            </div>
                            
                            " . ($notes ? "<p><strong>Notes from reviewer:</strong><br>{$notes}</p>" : "") . "
                            
                            <p>Our team will contact you shortly to discuss the next steps for setting up your franchise branch.</p>
                            
                            <p style='margin-top: 30px;'>Best regards,<br><strong>ChakaNoks SCMS Team</strong></p>
                        </div>
                    </div>
                </body>
                </html>";
            } else {
                $email->setSubject('Update on Your Franchise Application - ChakaNoks SCMS');
                $message = "
                <html>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <div style='background: linear-gradient(135deg, #6b7280, #4b5563); padding: 20px; border-radius: 10px 10px 0 0;'>
                            <h1 style='color: white; margin: 0;'>Application Update</h1>
                        </div>
                        <div style='background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;'>
                            <p>Dear <strong>{$application['applicant_name']}</strong>,</p>
                            <p>Thank you for your interest in becoming a ChakaNoks franchise partner.</p>
                            <p>After careful review, we regret to inform you that your application has not been approved at this time.</p>
                            
                            <div style='background: white; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #6b7280;'>
                                <p style='margin: 0;'><strong>Application Number:</strong> {$application['application_number']}</p>
                            </div>
                            
                            " . ($notes ? "<p><strong>Feedback:</strong><br>{$notes}</p>" : "") . "
                            
                            <p>We encourage you to apply again in the future if your circumstances change.</p>
                            
                            <p style='margin-top: 30px;'>Best regards,<br><strong>ChakaNoks SCMS Team</strong></p>
                        </div>
                    </div>
                </body>
                </html>";
            }
            
            $email->setMessage($message);
            $email->setMailType('html');
            
            if ($email->send()) {
                log_message('info', "Successfully sent {$status} email to: " . $application['email']);
            } else {
                log_message('error', 'Failed to send franchise email: ' . $email->printDebugger(['headers', 'subject', 'body']));
            }
        } catch (\Exception $e) {
            log_message('error', 'Email notification error: ' . $e->getMessage());
        }
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
