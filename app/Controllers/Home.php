<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Home - ChakaNoks SCMS',
            'page_title' => 'Welcome to ChakaNoks SCMS'
        ];
        return view('index', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'About Us - ChakaNoks SCMS',
            'page_title' => 'About ChakaNoks SCMS'
        ];
        return view('about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact Us - ChakaNoks SCMS',
            'page_title' => 'Contact Us'
        ];
        return view('contact', $data);
    }

    public function franchiseApplication()
    {
        $data = [
            'title' => 'Franchise Application - ChakaNoks SCMS',
            'page_title' => 'Franchise Application'
        ];
        return view('franchise_application', $data);
    }

    public function submitFranchiseApplication()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'phone_number' => 'required|max_length[20]',
            'address' => 'required|min_length[10]|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Get form data
        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number'),
            'address' => $this->request->getPost('address'),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Try to save to database
        // Note: You may need to create a franchise_applications table
        // For now, we'll store it in a way that works with or without the table
        try {
            $db = \Config\Database::connect();
            
            // Check if franchise_applications table exists
            if ($db->tableExists('franchise_applications')) {
                $db->table('franchise_applications')->insert($data);
            } else {
                // If table doesn't exist, log the application for manual processing
                // In production, you should create the table or send an email notification
                log_message('info', 'Franchise Application Received: ' . json_encode($data));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the submission
            log_message('error', 'Franchise application submission error: ' . $e->getMessage());
        }

        // Set success message
        session()->setFlashdata('success', 'Your franchise application has been successfully submitted!');

        return redirect()->to('contact');
    }
}
