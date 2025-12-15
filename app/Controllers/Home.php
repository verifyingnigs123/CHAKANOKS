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
            'full_name' => [
                'rules' => 'required|min_length[3]|max_length[150]|regex_match[/^[A-Za-zÑñ\s]+$/]',
                'errors' => [
                    'regex_match' => 'Name must contain only letters (Ñ/ñ allowed), no numbers or special characters.'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|max_length[150]|regex_match[/^[a-zA-Z0-9.]+@[a-zA-Z0-9]+\.[a-zA-Z]+$/]',
                'errors' => [
                    'regex_match' => 'Email must not contain special characters.'
                ]
            ],
            'phone_number' => [
                'rules' => 'required|exact_length[11]|regex_match[/^09[0-9]{9}$/]',
                'errors' => [
                    'exact_length' => 'Phone number must be exactly 11 digits.',
                    'regex_match' => 'Phone number must start with 09 (Philippine mobile format).'
                ]
            ],
            'address' => 'required|min_length[10]|max_length[500]',
            'investment_capital' => [
                'rules' => 'required|numeric|greater_than[0]|less_than_equal_to[100000000]',
                'errors' => [
                    'less_than_equal_to' => 'Investment capital must not exceed ₱100,000,000.'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        // Additional server-side sanitization
        $fullName = preg_replace('/[^A-Za-zÑñ\s]/', '', $this->request->getPost('full_name'));
        $phoneNumber = preg_replace('/\D/', '', $this->request->getPost('phone_number'));

        // Use FranchiseApplicationModel
        $franchiseModel = new \App\Models\FranchiseApplicationModel();
        
        // Extract city from address (use first part or default)
        $address = $this->request->getPost('address');
        $addressParts = array_map('trim', explode(',', $address));
        $city = count($addressParts) >= 2 ? $addressParts[count($addressParts) - 2] : 'N/A';
        $province = count($addressParts) >= 1 ? $addressParts[count($addressParts) - 1] : 'N/A';
        
        $data = [
            'application_number' => $franchiseModel->generateApplicationNumber(),
            'applicant_name' => $fullName,
            'email' => $this->request->getPost('email'),
            'phone' => $phoneNumber,
            'business_name' => $this->request->getPost('business_name'),
            'proposed_location' => $address,
            'city' => $city,
            'province' => $province,
            'investment_capital' => $this->request->getPost('investment_capital'),
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
