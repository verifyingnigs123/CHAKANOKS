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
            'full_name' => 'required|min_length[3]|max_length[150]',
            'email' => 'required|valid_email|max_length[150]',
            'phone_number' => 'required|max_length[20]',
            'address' => 'required|min_length[10]|max_length[500]',
            'city' => 'required|max_length[100]',
            'province' => 'required|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Use FranchiseApplicationModel
        $franchiseModel = new \App\Models\FranchiseApplicationModel();
        
        $data = [
            'application_number' => $franchiseModel->generateApplicationNumber(),
            'applicant_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone_number'),
            'business_name' => $this->request->getPost('business_name'),
            'proposed_location' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'province' => $this->request->getPost('province'),
            'investment_capital' => $this->request->getPost('investment_capital') ?: null,
            'business_experience' => $this->request->getPost('business_experience'),
            'motivation' => $this->request->getPost('motivation'),
            'status' => 'pending',
        ];

        try {
            $franchiseModel->insert($data);
            
            // Notify franchise manager
            $notificationService = new \App\Libraries\NotificationService();
            $notificationService->sendToRole('franchise_manager', 'info', 'New Franchise Application', "New application {$data['application_number']} from {$data['applicant_name']}", base_url('franchise/applications'));
            
            session()->setFlashdata('success', 'Your franchise application has been successfully submitted! Application Number: ' . $data['application_number']);
        } catch (\Exception $e) {
            log_message('error', 'Franchise application submission error: ' . $e->getMessage());
            session()->setFlashdata('error', 'There was an error submitting your application. Please try again.');
            return redirect()->back()->withInput();
        }

        return redirect()->to('franchise-application');
    }
}
