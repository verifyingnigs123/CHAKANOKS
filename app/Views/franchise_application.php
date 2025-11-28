<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= esc($title ?? 'Franchise Application - ChakaNoks SCMS') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-blue: #1e40af;
      --dark-blue: #1e3a8a;
      --light-blue: #3b82f6;
      --primary-green: #10b981;
      --accent-orange: #f97316;
      --text-dark: #0f172a;
      --text-light: #64748b;
      --bg-light: #ffffff;
      --bg-section: #f8fafc;
    }
    
    * {
      font-family: 'Inter', 'Poppins', system-ui, sans-serif;
    }
    
    body {
      background: var(--bg-section);
      color: var(--text-dark);
      line-height: 1.7;
    }
    
    /* Navigation */
    .navbar {
      background: var(--dark-blue) !important;
      padding: 1.2rem 0;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .navbar-brand {
      font-weight: 700;
      font-size: 1.4rem;
      color: white !important;
      display: flex;
      align-items: center;
      gap: 0.6rem;
      letter-spacing: -0.5px;
    }
    
    .navbar-brand i {
      font-size: 1.6rem;
      color: #60a5fa;
    }
    
    .nav-link {
      color: rgba(255,255,255,0.9) !important;
      font-weight: 500;
      font-size: 0.95rem;
      margin: 0 0.3rem;
      transition: all 0.3s ease;
      padding: 0.5rem 1rem !important;
      position: relative;
    }
    
    .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 2px;
      background: #60a5fa;
      transition: width 0.3s ease;
    }
    
    .nav-link:hover, .nav-link.active {
      color: white !important;
    }
    
    .nav-link:hover::after, .nav-link.active::after {
      width: 80%;
    }
    
    .btn-login {
      background: white;
      color: var(--dark-blue) !important;
      font-weight: 600;
      padding: 0.6rem 1.8rem;
      border-radius: 8px;
      transition: all 0.3s ease;
      border: none;
      font-size: 0.95rem;
    }
    
    .btn-login:hover {
      background: #e0e7ff;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    /* Hero Section */
    .hero-section {
      background: linear-gradient(135deg, var(--dark-blue) 0%, var(--primary-blue) 100%);
      padding: 140px 0 80px;
      text-align: center;
      color: white;
    }
    
    .hero-title {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 1rem;
      color: white;
      letter-spacing: -1px;
    }
    
    .hero-subtitle {
      font-size: 1.15rem;
      color: rgba(255,255,255,0.9);
      max-width: 700px;
      margin: 0 auto;
      line-height: 1.8;
      font-weight: 400;
    }
    
    /* Content Section */
    .content-section {
      padding: 100px 0;
      background: var(--bg-section);
    }
    
    /* Info Box */
    .info-box {
      background: white;
      border-left: 4px solid var(--primary-blue);
      border-radius: 16px;
      padding: 2rem;
      margin-bottom: 3rem;
      box-shadow: 0 2px 20px rgba(0,0,0,0.06);
      border: 1px solid rgba(0,0,0,0.05);
    }
    
    .info-box h5 {
      font-weight: 700;
      margin-bottom: 0.75rem;
      color: var(--text-dark);
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 1.1rem;
    }
    
    .info-box h5 i {
      color: var(--primary-blue);
      font-size: 1.4rem;
    }
    
    .info-box p {
      color: var(--text-light);
      line-height: 1.8;
      margin: 0;
      font-size: 0.95rem;
    }
    
    /* Form Section */
    .form-section {
      background: white;
      border-radius: 24px;
      padding: 3.5rem;
      box-shadow: 0 2px 20px rgba(0,0,0,0.06);
      margin-bottom: 3rem;
      border: 1px solid rgba(0,0,0,0.05);
    }
    
    .form-section h2 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 2.5rem;
      color: var(--text-dark);
      display: flex;
      align-items: center;
      gap: 1rem;
      letter-spacing: -0.5px;
    }
    
    .form-section h2 i {
      color: var(--primary-blue);
      font-size: 2rem;
    }
    
    .form-control, .form-select {
      border-radius: 12px;
      border: 2px solid #e5e7eb;
      padding: 0.875rem 1.25rem;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: var(--primary-blue);
      box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.15);
      outline: none;
    }
    
    .form-label {
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 0.75rem;
      font-size: 0.95rem;
    }
    
    .form-label .required {
      color: #dc3545;
    }
    
    .btn-submit {
      background: var(--primary-blue);
      border: none;
      color: white;
      font-weight: 600;
      padding: 1rem 3rem;
      border-radius: 12px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
      font-size: 1rem;
    }
    
    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(30, 64, 175, 0.4);
      background: var(--dark-blue);
      color: white;
    }
    
    /* Success Message */
    .success-message {
      background: white;
      border: 2px solid var(--primary-green);
      border-radius: 24px;
      padding: 5rem 3rem;
      text-align: center;
      margin: 2rem 0;
      box-shadow: 0 8px 40px rgba(16, 185, 129, 0.15);
    }
    
    .success-message i {
      font-size: 5rem;
      color: var(--primary-green);
      margin-bottom: 1.5rem;
      animation: scaleIn 0.5s ease;
    }
    
    @keyframes scaleIn {
      from { transform: scale(0); }
      to { transform: scale(1); }
    }
    
    .success-message h3 {
      font-size: 2.4rem;
      font-weight: 800;
      margin-bottom: 1rem;
      color: var(--text-dark);
      letter-spacing: -0.5px;
    }
    
    .success-message p {
      font-size: 1.15rem;
      color: var(--text-light);
      line-height: 1.8;
      max-width: 700px;
      margin: 0 auto 2rem;
    }
    
    .btn-return {
      background: var(--primary-blue);
      color: white;
      font-weight: 600;
      padding: 1rem 3rem;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
      display: inline-block;
      font-size: 1rem;
    }
    
    .btn-return:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(30, 64, 175, 0.4);
      background: var(--dark-blue);
      color: white;
    }
    
    /* Footer */
    footer {
      background: var(--dark-blue);
      color: white;
      padding: 4rem 0 2rem;
    }
    
    .footer-content {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 3rem;
      margin-bottom: 3rem;
    }
    
    .footer-section h5 {
      font-weight: 700;
      margin-bottom: 1.25rem;
      color: white;
      font-size: 1.1rem;
    }
    
    .footer-section p {
      color: rgba(255,255,255,0.8);
      line-height: 1.8;
      font-size: 0.95rem;
    }
    
    .footer-section a {
      color: rgba(255,255,255,0.75);
      text-decoration: none;
      display: block;
      margin-bottom: 0.75rem;
      transition: all 0.3s ease;
      font-size: 0.95rem;
    }
    
    .footer-section a:hover {
      color: white;
      transform: translateX(5px);
    }
    
    .footer-bottom {
      text-align: center;
      padding-top: 2.5rem;
      border-top: 1px solid rgba(255,255,255,0.1);
      color: rgba(255,255,255,0.7);
      font-size: 0.9rem;
    }
    
    @media (max-width: 768px) {
      .hero-title {
        font-size: 2.2rem;
      }
      .form-section {
        padding: 2.5rem 2rem;
      }
      .success-message {
        padding: 4rem 2rem;
      }
      .success-message h3 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand" href="<?= base_url('/') ?>">
        <i class="bi bi-shop"></i>
        <span>ChakaNoks SCMS</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url('/') ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url('about') ?>">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url('contact') ?>">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url('login') ?>">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <div class="hero-section">
    <div class="container">
      <h1 class="hero-title">Start Your Partnership With ChakaNoks</h1>
      <p class="hero-subtitle">
        Join us in bringing quality Filipino food to communities nationwide. 
        Fill out the form below to begin your franchise application.
      </p>
    </div>
  </div>

  <!-- Content Section -->
  <div class="content-section">
    <div class="container">
      <?php if (session()->getFlashdata('success')): ?>
        <div class="success-message">
          <i class="bi bi-check-circle-fill"></i>
          <h3>Application Submitted Successfully!</h3>
          <p>
            Your franchise application has been successfully sent. Our central admin will contact you soon 
            to discuss the next steps in your ChakaNoks journey.
          </p>
          <a href="<?= base_url('/') ?>" class="btn-return">
            <i class="bi bi-house"></i> Return to Home
          </a>
        </div>
      <?php else: ?>
        <div class="info-box">
          <h5>
            <i class="bi bi-info-circle"></i>
            Application Information
          </h5>
          <p>
            Please fill out all required fields accurately. Our team will review your application and contact you 
            within 5-7 business days. All information provided will be kept confidential and used solely for 
            franchise evaluation purposes.
          </p>
        </div>

        <!-- Application Form -->
        <div class="form-section">
          <h2>
            <i class="bi bi-file-earmark-text"></i>
            Franchise Application Form
          </h2>
          <form method="post" action="<?= base_url('franchise-application/submit') ?>" id="franchiseForm">
            <?= csrf_field() ?>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="full_name" class="form-label">
                  Full Name <span class="required">*</span>
                </label>
                <input type="text" class="form-control" id="full_name" name="full_name" required placeholder="Enter your full name">
              </div>
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">
                  Email Address <span class="required">*</span>
                </label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="your.email@example.com">
              </div>
            </div>
            
            <div class="mb-3">
              <label for="phone_number" class="form-label">
                Phone Number <span class="required">*</span>
              </label>
              <input type="tel" class="form-control" id="phone_number" name="phone_number" required placeholder="+63 XXX XXX XXXX">
            </div>
            
            <div class="mb-4">
              <label for="address" class="form-label">
                Address / Location of Proposed Branch <span class="required">*</span>
              </label>
              <textarea class="form-control" id="address" name="address" rows="3" required placeholder="Enter the proposed location address for your franchise branch"></textarea>
            </div>
            
            <div class="text-center">
              <button type="submit" class="btn btn-submit">
                <i class="bi bi-send-fill"></i> Submit Application
              </button>
            </div>
          </form>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="footer-content">
        <div class="footer-section">
          <h5>ChakaNoks SCMS</h5>
          <p>
            Empowering food businesses with efficient supply chain management solutions.
          </p>
        </div>
        <div class="footer-section">
          <h5>Quick Links</h5>
          <a href="<?= base_url('/') ?>">Home</a>
          <a href="<?= base_url('about') ?>">About Us</a>
          <a href="<?= base_url('contact') ?>">Contact</a>
          <a href="<?= base_url('franchise-application') ?>">Franchise Application</a>
        </div>
        <div class="footer-section">
          <h5>Resources</h5>
          <a href="<?= base_url('about') ?>">Our Mission</a>
          <a href="<?= base_url('about') ?>">Future Plans</a>
          <a href="<?= base_url('contact') ?>">Support</a>
        </div>
        <div class="footer-section">
          <h5>Contact Info</h5>
          <p style="margin-bottom: 0.5rem;">
            <i class="bi bi-envelope"></i> info@chakanoks.com
          </p>
          <p style="margin-bottom: 0.5rem;">
            <i class="bi bi-telephone"></i> +63 (82) 123-4567
          </p>
          <p>
            <i class="bi bi-geo-alt"></i> Davao City, Philippines
          </p>
        </div>
      </div>
      <div class="footer-bottom">
        <p class="mb-0">&copy; <?= date('Y') ?> ChakaNoks SCMS. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Form validation
    const form = document.getElementById('franchiseForm');
    if (form) {
      form.addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
          if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
          } else {
            field.classList.remove('is-invalid');
          }
        });
        
        if (!isValid) {
          e.preventDefault();
          alert('Please fill in all required fields.');
        }
      });
    }
  </script>
</body>
</html>
