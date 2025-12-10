<?php

namespace App\Controllers;

use App\Models\UserModel; 
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function index()
    {
        helper(['form']);
        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $userModel = new UserModel(); // ✅ now works

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user) {
            if ($user['status'] !== 'active') {
                $session->setFlashdata('msg', 'Your account is inactive. Please contact the administrator.');
                return redirect()->to('/login');
            }

            $verify_pass = password_verify($password, $user['password']);
            if ($verify_pass) {
                // Fetch branch name if user has a branch_id
                $branchName = null;
                if (!empty($user['branch_id'])) {
                    $branchModel = new \App\Models\BranchModel();
                    $branch = $branchModel->find($user['branch_id']);
                    $branchName = $branch ? $branch['name'] : null;
                }
                
                $session_data = [
                    'user_id'   => $user['id'],
                    'username'  => $user['username'],
                    'email'     => $user['email'],
                    'full_name' => $user['full_name'] ?? $user['username'],
                    'role'      => $user['role'],
                    'branch_id' => $user['branch_id'] ?? null,
                    'branch_name' => $branchName,
                    'supplier_id' => $user['supplier_id'] ?? null,
                    'isLoggedIn' => true
                ];
                $session->set($session_data);

                // Update last login
                $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

                // Log activity
                $activityLogModel = new \App\Models\ActivityLogModel();
                $activityLogModel->logActivity($user['id'], 'login', 'auth', 'User logged in');

                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('msg', 'Wrong password.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Email not found.');
            return redirect()->to('/login');
        }
    }

    public function dashboard()
    {
        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        $session = session();
        
        // Log activity before destroying session
        if ($session->get('isLoggedIn')) {
            $activityLogModel = new \App\Models\ActivityLogModel();
            $activityLogModel->logActivity($session->get('user_id'), 'logout', 'auth', 'User logged out');
        }
        
        $session->destroy();
        return redirect()->to('/login');
    }
}
