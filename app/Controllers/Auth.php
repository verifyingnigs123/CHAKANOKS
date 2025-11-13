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
                return redirect()->to('/auth');
            }

            $verify_pass = password_verify($password, $user['password']);
            if ($verify_pass) {
                $session_data = [
                    'user_id'   => $user['id'],
                    'username'  => $user['username'],
                    'email'     => $user['email'],
                    'role'      => $user['role'],
                    'isLoggedIn' => true
                ];
                $session->set($session_data);

                return redirect()->to('/auth/dashboard');
            } else {
                $session->setFlashdata('msg', 'Wrong password.');
                return redirect()->to('/auth');
            }
        } else {
            $session->setFlashdata('msg', 'Email not found.');
            return redirect()->to('/auth');
        }
    }

    public function dashboard()
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }

        $data['role'] = $session->get('role');
        $data['username'] = $session->get('username');

        return view('auth/dashboard', $data);
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
