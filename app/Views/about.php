<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= esc($title ?? 'About - ChakaNoks SCMS') ?></title>
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
      background: var(--bg-light);
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
      background: var(--bg-light);
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
    
    .section-content {
      color: var(--text-light);
      line-height: 1.8;
      font-size: 1.05rem;
      max-width: 800px;
      margin: 0 auto;
    }
    
    .mission-vision-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      margin: 3rem 0;
    }
    
    .mission-card {
      background: white;
      border: 1px solid rgba(0,0,0,0.05);
      border-radius: 20px;
      padding: 2.5rem;
      box-shadow: 0 2px 20px rgba(0,0,0,0.06);
      transition: all 0.4s ease;
    }
    
    .mission-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    }
    
    .mission-card h4 {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: var(--text-dark);
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    
    .mission-card h4 i {
      color: var(--primary-blue);
      font-size: 1.8rem;
    }
    
    .mission-card p {
      color: var(--text-light);
      line-height: 1.8;
      margin: 0;
    }
    
    .info-box {
      background: var(--bg-section);
      border-radius: 20px;
      padding: 2.5rem;
      margin: 3rem 0;
      border: 1px solid rgba(0,0,0,0.05);
    }
    
    .info-box h4 {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      color: var(--text-dark);
    }
    
    .info-box ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .info-box ul li {
      padding: 1rem 0;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      display: flex;
      align-items: start;
      gap: 1rem;
    }
    
    .info-box ul li:last-child {
      border-bottom: none;
    }
    
    .info-box ul li i {
      color: var(--primary-green);
      font-size: 1.3rem;
      margin-top: 0.25rem;
      flex-shrink: 0;
    }
    
    .info-box ul li div {
      color: var(--text-light);
      line-height: 1.8;
    }
    
    .info-box ul li strong {
      color: var(--text-dark);
      display: block;
      margin-bottom: 0.25rem;
    }
    
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      margin: 3rem 0;
    }
    
    .feature-card {
      background: white;
      border: 1px solid rgba(0,0,0,0.05);
      border-radius: 20px;
      padding: 2rem;
      transition: all 0.4s ease;
      box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    }
    
    .feature-card:hover {
      box-shadow: 0 8px 30px rgba(0,0,0,0.1);
      transform: translateY(-5px);
    }
    
    .feature-icon {
      width: 60px;
      height: 60px;
      border-radius: 16px;
      background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.75rem;
      margin-bottom: 1.25rem;
    }
    
    .feature-card h5 {
      font-size: 1.25rem;
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
    
    .cta-section {
      background: linear-gradient(135deg, var(--dark-blue) 0%, var(--primary-blue) 100%);
      border-radius: 24px;
      padding: 4rem 3rem;
      text-align: center;
      color: white;
      margin: 4rem 0;
      box-shadow: 0 8px 40px rgba(0,0,0,0.15);
    }
    
    .cta-section h3 {
      font-size: 2.4rem;
      font-weight: 800;
      margin-bottom: 1rem;
      color: white;
      letter-spacing: -0.5px;
    }
    
    .cta-section p {
      font-size: 1.1rem;
      color: rgba(255,255,255,0.9);
      margin-bottom: 2rem;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      font-weight: 400;
    }
    
    .btn-cta {
      background: white;
      color: var(--dark-blue);
      font-weight: 600;
      padding: 1rem 3rem;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
      display: inline-block;
      font-size: 1rem;
    }
    
    .btn-cta:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.3);
      color: var(--dark-blue);
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
      .section-title {
        font-size: 2rem;
      }
      .mission-vision-grid {
        grid-template-columns: 1fr;
      }
      .cta-section {
        padding: 3rem 2rem;
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
            <a class="nav-link active" href="<?= base_url('about') ?>">About</a>
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
      <h1 class="hero-title">About ChakaNoks & Our Future Plans</h1>
      <p class="hero-subtitle">
        Delivering quality Filipino food through innovative supply chain management and strategic growth.
      </p>
    </div>
  </div>

  <!-- Content Section -->
  <div class="content-section">
    <div class="container">
      <!-- Company Overview -->
      <div class="section-header">
        <h2 class="section-title">Company Overview</h2>
      </div>
      
      <div class="section-content">
        <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-dark);">History of ChakaNoks</h3>
        <p style="margin-bottom: 3rem;">
          ChakaNoks began as a local food business in Davao City, committed to delivering authentic Filipino flavors 
          to our community. Over the years, we've grown from a single location to multiple branches, building a reputation 
          for quality, consistency, and customer satisfaction.
        </p>
      </div>
      
      <div class="mission-vision-grid">
        <div class="mission-card">
          <h4>
            <i class="bi bi-eye-fill"></i>
            Our Vision
          </h4>
          <p>
            To become the leading Filipino food brand nationwide, recognized for consistent quality, operational excellence, 
            and sustainable growth through innovative supply chain management.
          </p>
        </div>
        <div class="mission-card">
          <h4>
            <i class="bi bi-bullseye"></i>
            Our Mission
          </h4>
          <p>
            To deliver exceptional food quality and customer experiences through centralized supply chain management, 
            ensuring consistency across all branches and franchise locations.
          </p>
        </div>
      </div>
      
      <div class="section-content" style="margin-top: 3rem;">
        <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-dark);">Commitment to Quality</h3>
        <p>
          At ChakaNoks, consistent food quality is the foundation of our brand. We ensure that every customer, 
          whether at our flagship branch or a franchise location, receives the same high-quality experience.
        </p>
      </div>

      <!-- SCMS Explanation -->
      <div class="section-header" style="margin-top: 5rem;">
        <h2 class="section-title">Why Our SCMS is Essential</h2>
      </div>
      
      <div class="info-box">
        <h4>How SCMS Supports Branches and Franchise Partners</h4>
        <ul>
          <li>
            <i class="bi bi-check-circle-fill"></i>
            <div>
              <strong>Real-time Inventory Visibility</strong>
              All branches and franchise partners have instant access to inventory levels, enabling better planning and reducing stockouts.
            </div>
          </li>
          <li>
            <i class="bi bi-check-circle-fill"></i>
            <div>
              <strong>Automated Procurement</strong>
              Streamlined purchase request and approval workflows ensure timely ordering while maintaining quality standards.
            </div>
          </li>
          <li>
            <i class="bi bi-check-circle-fill"></i>
            <div>
              <strong>Centralized Quality Control</strong>
              The system tracks product quality, expiration dates, and supplier performance, ensuring all locations maintain high standards.
            </div>
          </li>
          <li>
            <i class="bi bi-check-circle-fill"></i>
            <div>
              <strong>Inter-Branch Coordination</strong>
              Efficient transfer management allows branches to support each other, optimizing inventory distribution.
            </div>
          </li>
        </ul>
      </div>

      <!-- Future Plans -->
      <div class="section-header" style="margin-top: 5rem;">
        <h2 class="section-title">Future Plans & Expansion</h2>
      </div>
      
      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-geo-alt-fill"></i>
          </div>
          <h5>New Branch Expansion</h5>
          <p>Opening a new branch outside Davao City to extend our reach and serve more customers nationwide.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-building-fill"></i>
          </div>
          <h5>Central Office</h5>
          <p>Establishing a Central Office to oversee transactions, manage franchising, and coordinate multi-branch activities.</p>
        </div>
      </div>

      <!-- Franchise Opportunity -->
      <div class="section-header" style="margin-top: 5rem;">
        <h2 class="section-title">Franchise Opportunity</h2>
      </div>
      
      <div class="section-content">
        <p style="margin-bottom: 3rem;">
          Join the ChakaNoks family and become part of a growing Filipino food brand. Our franchise model is designed to 
          support your success while maintaining the quality and consistency that customers expect.
        </p>
      </div>
      
      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-gear"></i>
          </div>
          <h5>Operations Support</h5>
          <p>Comprehensive operational guidance and standard operating procedures for smooth daily operations.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-truck"></i>
          </div>
          <h5>Supply Chain Management</h5>
          <p>Access to our centralized SCMS, automated procurement, and supplier coordination.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-people"></i>
          </div>
          <h5>HR & Training</h5>
          <p>Staff training programs, recruitment support, and human resources guidance.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-megaphone"></i>
          </div>
          <h5>Marketing Support</h5>
          <p>Brand marketing materials, promotional campaigns, and marketing strategies.</p>
        </div>
      </div>

      <!-- CTA Section -->
      <div class="cta-section">
        <h3>Ready to Start Your ChakaNoks Journey?</h3>
        <p>
          Take the first step toward becoming a ChakaNoks franchise partner. Our team is ready to guide you.
        </p>
        <a href="<?= base_url('franchise-application') ?>" class="btn-cta">
          <i class="bi bi-rocket-takeoff"></i> Apply for a ChakaNoks Franchise
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
