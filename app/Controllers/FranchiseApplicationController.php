<?php

namespace App\Controllers;

use App\Models\ActivityLogModel;
use App\Models\FranchiseModel;
use App\Libraries\Mailer;

class FranchiseApplicationController extends BaseController
{
    protected $franchiseModel;
    protected $activityLogModel;
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        $builder = $this->db->table('franchise_applications');
        
        // Search functionality
        $search = $this->request->getGet('search');
        if ($search) {
            $builder->groupStart()
                ->like('full_name', $search)
                ->orLike('email', $search)
                ->orLike('phone_number', $search)
                ->orLike('address', $search)
                ->groupEnd();
        }

        // Filter by status
        $status = $this->request->getGet('status');
        if ($status) {
            $builder->where('status', $status);
        }

        $data['applications'] = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
        $data['page_title'] = 'Franchise Applications';
        $data['title'] = 'Franchise Applications - ChakaNoks SCMS';

        return view('franchise_applications/index', $data);
    }

    public function view($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        $application = $this->db->table('franchise_applications')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!$application) {
            return redirect()->to('franchise-applications')->with('error', 'Application not found');
        }

        $data['application'] = $application;
        $data['page_title'] = 'View Franchise Application';
        $data['title'] = 'View Application - ChakaNoks SCMS';

        return view('franchise_applications/view', $data);
    }

    public function approve($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        $application = $this->db->table('franchise_applications')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!$application) {
            return redirect()->to('franchise-applications')->with('error', 'Application not found');
        }

        // Update status
        $this->db->table('franchise_applications')
            ->where('id', $id)
            ->update([
                'status' => 'approved',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        // Send approval email
        $this->sendApplicationStatusEmail($application['email'], $application['full_name'], 'approved');

        // Log activity
        $this->activityLogModel->insert([
            'user_id' => $session->get('user_id'),
            'action' => 'approve',
            'module' => 'franchise_application',
            'description' => "Approved franchise application from {$application['full_name']}",
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('franchise-applications')->with('success', 'Application approved and email notification sent!');
    }

    public function reject($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        $application = $this->db->table('franchise_applications')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!$application) {
            return redirect()->to('franchise-applications')->with('error', 'Application not found');
        }

        // Update status
        $this->db->table('franchise_applications')
            ->where('id', $id)
            ->update([
                'status' => 'rejected',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        // Send rejection email
        $this->sendApplicationStatusEmail($application['email'], $application['full_name'], 'rejected');

        // Log activity
        $this->activityLogModel->insert([
            'user_id' => $session->get('user_id'),
            'action' => 'reject',
            'module' => 'franchise_application',
            'description' => "Rejected franchise application from {$application['full_name']}",
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('franchise-applications')->with('success', 'Application rejected and email notification sent!');
    }

    private function sendApplicationStatusEmail($toEmail, $fullName, $status)
    {
        $mailer = new Mailer();

        if ($status === 'approved') {
            $subject = 'Congratulations! Your ChakaNoks Franchise Application Has Been Approved';
            $body = $this->getApprovalEmailTemplate($fullName);
        } else {
            $subject = 'ChakaNoks Franchise Application Update';
            $body = $this->getRejectionEmailTemplate($fullName);
        }

        $sent = $mailer->sendHtml($toEmail, $subject, $body, $fullName);

        if ($sent) {
            log_message('info', "Franchise application status email sent to {$toEmail} - Status: {$status}");
        } else {
            log_message('error', "Failed to send email to {$toEmail} - Status: {$status}");
        }
    }

    private function getApprovalEmailTemplate($fullName)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f8fafc; padding: 30px; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; background: #1e40af; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
                .footer { text-align: center; margin-top: 30px; color: #64748b; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎉 Congratulations!</h1>
                </div>
                <div class='content'>
                    <h2>Dear {$fullName},</h2>
                    <p>We are pleased to inform you that your franchise application with ChakaNoks has been <strong>APPROVED</strong>!</p>
                    <p>Our team has reviewed your application and we are excited to welcome you as a ChakaNoks franchise partner.</p>
                    <p><strong>What's Next?</strong></p>
                    <ul>
                        <li>Our franchise team will contact you within 3-5 business days to discuss the next steps</li>
                        <li>We will guide you through the franchise agreement process</li>
                        <li>You will receive comprehensive training and support materials</li>
                    </ul>
                    <p>If you have any questions, please don't hesitate to contact us at <strong>franchise@chakanoks.com</strong> or call us at <strong>+63 (82) 123-4567</strong>.</p>
                    <p>We look forward to partnering with you in bringing quality Filipino food to your community!</p>
                    <p>Best regards,<br><strong>ChakaNoks Franchise Team</strong></p>
                </div>
                <div class='footer'>
                    <p>This is an automated email. Please do not reply to this message.</p>
                    <p>&copy; " . date('Y') . " ChakaNoks SCMS. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    private function getRejectionEmailTemplate($fullName)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f8fafc; padding: 30px; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; background: #1e40af; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
                .footer { text-align: center; margin-top: 30px; color: #64748b; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>ChakaNoks Franchise Application</h1>
                </div>
                <div class='content'>
                    <h2>Dear {$fullName},</h2>
                    <p>Thank you for your interest in becoming a ChakaNoks franchise partner.</p>
                    <p>After careful review of your application, we regret to inform you that we are unable to proceed with your franchise application at this time.</p>
                    <p>This decision was made based on our current franchise criteria and expansion plans. We encourage you to reapply in the future as our franchise program continues to grow.</p>
                    <p>If you have any questions about this decision or would like feedback, please feel free to contact us at <strong>franchise@chakanoks.com</strong> or call us at <strong>+63 (82) 123-4567</strong>.</p>
                    <p>We appreciate your interest in ChakaNoks and wish you success in your future endeavors.</p>
                    <p>Best regards,<br><strong>ChakaNoks Franchise Team</strong></p>
                </div>
                <div class='footer'>
                    <p>This is an automated email. Please do not reply to this message.</p>
                    <p>&copy; " . date('Y') . " ChakaNoks SCMS. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}

