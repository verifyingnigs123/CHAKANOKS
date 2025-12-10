<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ActivityLogModel;

class ProfileController extends BaseController
{
    protected $userModel;
    protected $activityLogModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = $session->get('user_id');
        $data['user'] = $this->userModel->find($userId);
        $data['title'] = 'My Profile';
        $data['page_title'] = 'My Profile';

        return view('profile/index', $data);
    }

    public function update()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = $session->get('user_id');
        
        $rules = [
            'username' => "required|min_length[3]|is_unique[users.username,id,{$userId}]",
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'full_name' => 'required|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
        ];

        $this->userModel->update($userId, $data);

        // Update session data
        $session->set('username', $data['username']);

        $this->activityLogModel->logActivity($userId, 'update', 'profile', 'Updated profile information');

        return redirect()->to('/profile')->with('success', 'Profile updated successfully');
    }

    public function changePassword()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = $session->get('user_id');
        $user = $this->userModel->find($userId);

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Verify current password
        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect');
        }

        // Validate new password
        if (strlen($newPassword) < 6) {
            return redirect()->back()->with('error', 'New password must be at least 6 characters');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'New passwords do not match');
        }

        // Update password
        $this->userModel->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);

        $this->activityLogModel->logActivity($userId, 'update', 'profile', 'Changed password');

        return redirect()->to('/profile')->with('success', 'Password changed successfully');
    }
}
