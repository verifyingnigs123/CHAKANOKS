<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function index()
    {
        $session = session();

        // Redirect if already logged in
        if ($session->get('isLoggedIn')) {
            return redirect()->to(base_url('auth/dashboard'));
        }

        return view('auth/login');
    }

    public function login()
    {
        $session = session();

        // If already logged in
        if ($session->get('isLoggedIn')) {
            return redirect()->to(base_url('auth/dashboard'));
        }

        if ($this->request->getMethod() === 'POST') {
            $username = trim($this->request->getPost('username'));
            $password = $this->request->getPost('password');

            $userModel = new UserModel();
            $user = $userModel->where('username', $username)->first();

            if ($user && password_verify($password, $user['password'])) {
                // Set session data
                $session->set([
                    'isLoggedIn' => true,
                    'user_id'    => $user['id'],
                    'username'   => $user['username'],
                    'role'       => $user['role'],
                ]);

                return redirect()->to(base_url('auth/dashboard'));
            }

            // If credentials are invalid
            $session->setFlashdata('error', 'Invalid username or password. Please try again.');
            return redirect()->to(base_url('auth'));
        }

        return redirect()->to(base_url('auth'));
    }

    public function dashboard()
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('auth'))->with('error', 'Please login first.');
        }

        $data = [
            'username' => $session->get('username'),
            'role'     => $session->get('role'),
        ];

        return view('auth/dashboard', $data);
    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        return redirect()->to(base_url('auth'))->with('success', 'You have logged out successfully.');
    }
}
