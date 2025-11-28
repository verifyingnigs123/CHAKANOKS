<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= esc($title ?? 'Home - ChakaNoks SCMS') ?></title>
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
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      background: var(--bg-light);
      color: var(--text-dark);
      line-height: 1.7;
      overflow-x: hidden;
    }
    
    /* Navigation */
    .navbar {
      background: var(--dark-blue) !important;
      padding: 1.2rem 0;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
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
      padding: 140px 0 100px;
      text-align: center;
      color: white;
      position: relative;
      overflow: hidden;
    }
    
    .hero-section::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      animation: pulse 15s ease-in-out infinite;
    }
    
    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 0.5; }
      50% { transform: scale(1.1); opacity: 0.3; }
    }
    
    .hero-content {
      position: relative;
      z-index: 1;
    }
    
    .hero-icon {
      width: 100px;
      height: 100px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 2rem;
      backdrop-filter: blur(20px);
      box-shadow: 0 8px 32px rgba(0,0,0,0.2);
      animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
    
    .hero-icon i {
      font-size: 3.5rem;
      color: white;
    }
    
    .hero-title {
      font-size: 3.2rem;
      font-weight: 800;
      margin-bottom: 1.5rem;
      color: white;
      letter-spacing: -1px;
      line-height: 1.2;
    }
    
    .hero-subtitle {
      font-size: 1.15rem;
      color: rgba(255,255,255,0.9);
      margin-bottom: 2.5rem;
      max-width: 650px;
      margin-left: auto;
      margin-right: auto;
      line-height: 1.8;
      font-weight: 400;
    }
    
    .hero-buttons {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
    }
    
    .btn-hero-primary {
      background: white;
      color: var(--dark-blue);
      font-weight: 600;
      padding: 1rem 2.5rem;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
      font-size: 1rem;
    }
    
    .btn-hero-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.3);
      color: var(--dark-blue);
    }
    
    .btn-hero-secondary {
      background: rgba(255, 255, 255, 0.15);
      color: white;
      font-weight: 600;
      padding: 1rem 2.5rem;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.3s ease;
      border: 2px solid rgba(255, 255, 255, 0.3);
      backdrop-filter: blur(10px);
      font-size: 1rem;
    }
    
    .btn-hero-secondary:hover {
      background: rgba(255, 255, 255, 0.25);
      color: white;
      transform: translateY(-3px);
      border-color: rgba(255, 255, 255, 0.5);
    }
    
    /* Features Section */
    .features-section {
      padding: 100px 0;
      background: var(--bg-section);
    }
    
    .section-header {
      text-align: center;
      margin-bottom: 4rem;
    }
    
    .section-title {
      font-size: 2.4rem;
      font-weight: 800;
      color: var(--text-dark);
      margin-bottom: 1rem;
      letter-spacing: -0.5px;
    }
    
    .section-subtitle {
      font-size: 1.1rem;
      color: var(--text-light);
      max-width: 600px;
      margin: 0 auto;
      font-weight: 400;
    }
    
    .feature-card {
      background: white;
      border-radius: 20px;
      padding: 2.5rem;
      height: 100%;
      box-shadow: 0 2px 20px rgba(0,0,0,0.06);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      border: 1px solid rgba(0,0,0,0.05);
      position: relative;
      overflow: hidden;
    }
    
    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-blue), var(--primary-green));
      transform: scaleX(0);
      transition: transform 0.4s ease;
    }
    
    .feature-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    }
    
    .feature-card:hover::before {
      transform: scaleX(1);
    }
    
    .feature-icon {
      width: 64px;
      height: 64px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.5rem;
      font-size: 1.8rem;
      color: white;
      background: linear-gradient(135deg, var(--primary-blue), var(--light-blue));
    }
    
    .feature-card:nth-child(2) .feature-icon {
      background: linear-gradient(135deg, #ef4444, #f87171);
    }
    
    .feature-card:nth-child(3) .feature-icon {
      background: linear-gradient(135deg, var(--primary-green), #34d399);
    }
    
    .feature-card:nth-child(4) .feature-icon {
      background: linear-gradient(135deg, var(--accent-orange), #fb923c);
    }
    
    .feature-card h4 {
      font-size: 1.35rem;
      font-weight: 700;
      margin-bottom: 0.75rem;
      color: var(--text-dark);
    }
    
    .feature-card p {
      color: var(--text-light);
      line-height: 1.7;
      margin: 0;
      font-size: 0.95rem;
    }
    
    /* Franchise Section */
    .franchise-section {
      background: linear-gradient(135deg, var(--dark-blue) 0%, var(--primary-blue) 100%);
      padding: 100px 0;
      color: white;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    
    .franchise-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse"><path d="M 60 0 L 0 0 0 60" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
    }
    
    .franchise-content {
      position: relative;
      z-index: 1;
    }
    
    .franchise-section h2 {
      font-size: 2.4rem;
      font-weight: 800;
      margin-bottom: 1rem;
      color: white;
      letter-spacing: -0.5px;
    }
    
    .franchise-section p {
      font-size: 1.1rem;
      color: rgba(255,255,255,0.9);
      margin-bottom: 3rem;
      max-width: 650px;
      margin-left: auto;
      margin-right: auto;
      font-weight: 400;
    }
    
    .benefits-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
      margin: 3rem 0;
      text-align: left;
    }
    
    .benefit-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      backdrop-filter: blur(10px);
      transition: all 0.3s ease;
    }
    
    .benefit-item:hover {
      background: rgba(255, 255, 255, 0.15);
      transform: translateX(5px);
    }
    
    .benefit-item i {
      font-size: 1.5rem;
      color: #60a5fa;
      flex-shrink: 0;
    }
    
    .benefit-item span {
      color: rgba(255, 255, 255, 0.95);
      font-size: 1rem;
      font-weight: 500;
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
    
    .social-links {
      display: flex;
      gap: 0.75rem;
      margin-top: 1.25rem;
    }
    
    .social-links a {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      transition: all 0.3s ease;
      margin: 0;
    }
    
    .social-links a:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-3px);
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
      .hero-subtitle {
        font-size: 1rem;
      }
      .section-title {
        font-size: 2rem;
      }
      .hero-buttons {
        flex-direction: column;
        align-items: stretch;
      }
      .hero-buttons a {
        width: 100%;
      }
      .benefits-grid {
        grid-template-columns: 1fr;
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
            <a class="nav-link active" href="<?= base_url('/') ?>">Home</a>
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
      <div class="hero-content">
        <div class="hero-icon">
          <i class="bi bi-box-seam"></i>
        </div>
        <h1 class="hero-title">Welcome to ChakaNoks SCMS</h1>
        <p class="hero-subtitle">
          Streamline your food supply chain with our comprehensive management system. 
          Experience efficiency, quality, and growth.
        </p>
        <div class="hero-buttons">
          <a href="<?= base_url('franchise-application') ?>" class="btn-hero-primary">
            <i class="bi bi-briefcase"></i> Apply for Franchise
          </a>
          <a href="<?= base_url('about') ?>" class="btn-hero-secondary">
            <i class="bi bi-info-circle"></i> Learn More
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Features Section -->
  <div class="features-section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Why Choose ChakaNoks SCMS?</h2>
        <p class="section-subtitle">
          Powerful tools designed to streamline your food supply chain operations
        </p>
      </div>
      
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-boxes"></i>
            </div>
            <h4>Centralized Inventory</h4>
            <p>Real-time tracking across all branches with automated alerts and barcode scanning.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-truck"></i>
            </div>
            <h4>Supplier Management</h4>
            <p>Streamlined procurement with automated requests, approvals, and performance tracking.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-speedometer2"></i>
            </div>
            <h4>Real-time Monitoring</h4>
            <p>Instant visibility into inventory levels with alerts for low stock or expiring items.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-graph-up-arrow"></i>
            </div>
            <h4>Franchise Support</h4>
            <p>Comprehensive support system for franchise partners including training and quality assurance.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Franchise Section -->
  <div class="franchise-section">
    <div class="container">
      <div class="franchise-content">
        <h2>Partner With ChakaNoks</h2>
        <p>
          Join us in bringing quality Filipino food to communities nationwide. 
          Receive comprehensive support to ensure your success.
        </p>
        
        <div class="benefits-grid">
          <div class="benefit-item">
            <i class="bi bi-check-circle-fill"></i>
            <span>Established Brand Recognition</span>
          </div>
          <div class="benefit-item">
            <i class="bi bi-check-circle-fill"></i>
            <span>Complete Supply Chain Support</span>
          </div>
          <div class="benefit-item">
            <i class="bi bi-check-circle-fill"></i>
            <span>Comprehensive Training Programs</span>
          </div>
          <div class="benefit-item">
            <i class="bi bi-check-circle-fill"></i>
            <span>Quality Assurance Standards</span>
          </div>
          <div class="benefit-item">
            <i class="bi bi-check-circle-fill"></i>
            <span>Marketing & Operations Support</span>
          </div>
          <div class="benefit-item">
            <i class="bi bi-check-circle-fill"></i>
            <span>Central Office Coordination</span>
          </div>
        </div>
        
        <a href="<?= base_url('franchise-application') ?>" class="btn-hero-primary" style="margin-top: 1rem;">
          <i class="bi bi-rocket-takeoff"></i> Start Your Franchise Application
        </a>
      </div>
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
          <div class="social-links">
            <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" title="Twitter"><i class="bi bi-twitter"></i></a>
            <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
          </div>
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
</body>
</html>
