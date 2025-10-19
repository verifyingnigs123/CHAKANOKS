<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | ChakaNoks SCMS</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 20px;
      position: relative;
      overflow: hidden;
    }

    /* Animated Background Elements */
    body::before,
    body::after {
      content: '';
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      animation: float 6s ease-in-out infinite;
    }

    body::before {
      width: 300px;
      height: 300px;
      top: -100px;
      left: -100px;
      animation-delay: 0s;
    }

    body::after {
      width: 400px;
      height: 400px;
      bottom: -150px;
      right: -150px;
      animation-delay: 3s;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(5deg); }
    }

    .login-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 450px;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 45px 40px;
      border-radius: 25px;
      box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
      position: relative;
      overflow: hidden;
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    }

    .logo-container {
      text-align: center;
      margin-bottom: 30px;
    }

    .logo {
      width: 90px;
      height: 90px;
      border-radius: 20px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      font-size: 40px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 15px;
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
      animation: logoFloat 3s ease-in-out infinite;
      position: relative;
    }

    .logo::after {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      border-radius: 20px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      opacity: 0;
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes logoFloat {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    @keyframes pulse {
      0% { transform: scale(1); opacity: 0.5; }
      50% { transform: scale(1.1); opacity: 0; }
      100% { transform: scale(1); opacity: 0; }
    }

    .login-card h3 {
      text-align: center;
      color: #2d3436;
      font-weight: 700;
      margin-bottom: 10px;
      font-size: 1.75rem;
    }

    .subtitle {
      text-align: center;
      color: #636e72;
      font-size: 0.9rem;
      margin-bottom: 30px;
    }

    .form-label {
      color: #2d3436;
      font-weight: 600;
      font-size: 0.9rem;
      margin-bottom: 8px;
    }

    .input-group-text {
      background: #f8f9fa;
      border: 1px solid #e0e0e0;
      border-right: none;
      color: #667eea;
    }

    .form-control {
      border: 1px solid #e0e0e0;
      padding: 12px 15px;
      border-radius: 10px;
      transition: all 0.3s ease;
      font-size: 0.95rem;
    }

    .input-group .form-control {
      border-left: none;
      border-radius: 0 10px 10px 0;
    }

    .input-group {
      border-radius: 10px;
      overflow: hidden;
    }

    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      outline: none;
    }

    .input-group:focus-within .input-group-text {
      border-color: #667eea;
      background: rgba(102, 126, 234, 0.05);
    }

    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #636e72;
      z-index: 10;
      transition: color 0.3s;
    }

    .password-toggle:hover {
      color: #667eea;
    }

    .password-wrapper {
      position: relative;
    }

    .form-check {
      margin: 20px 0;
    }

    .form-check-input {
      border: 2px solid #ddd;
      width: 18px;
      height: 18px;
      cursor: pointer;
    }

    .form-check-input:checked {
      background-color: #667eea;
      border-color: #667eea;
    }

    .form-check-label {
      color: #2d3436;
      font-size: 0.9rem;
      cursor: pointer;
      user-select: none;
    }

    .forgot-password {
      color: #667eea;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 600;
      transition: all 0.3s;
      float: right;
    }

    .forgot-password:hover {
      color: #764ba2;
      text-decoration: underline;
    }

    .btn-login {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      font-weight: 600;
      width: 100%;
      padding: 14px;
      border-radius: 12px;
      transition: all 0.3s ease;
      font-size: 1rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
      position: relative;
      overflow: hidden;
    }

    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s;
    }

    .btn-login:hover::before {
      left: 100%;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .alert {
      font-size: 0.9rem;
      border-radius: 10px;
      border: none;
      padding: 12px 15px;
      margin-bottom: 20px;
      animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-danger {
      background: rgba(220, 53, 69, 0.1);
      color: #dc3545;
      border-left: 4px solid #dc3545;
    }

    .alert-success {
      background: rgba(25, 135, 84, 0.1);
      color: #198754;
      border-left: 4px solid #198754;
    }

    .footer-text {
      text-align: center;
      color: #636e72;
      margin-top: 25px;
      font-size: 0.85rem;
    }

    .divider {
      text-align: center;
      margin: 25px 0;
      position: relative;
      color: #636e72;
      font-size: 0.85rem;
    }

    .divider::before,
    .divider::after {
      content: '';
      position: absolute;
      top: 50%;
      width: 40%;
      height: 1px;
      background: #e0e0e0;
    }

    .divider::before {
      left: 0;
    }

    .divider::after {
      right: 0;
    }

    .loading {
      display: none;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .loading-spinner {
      border: 3px solid rgba(255, 255, 255, 0.3);
      border-top: 3px solid white;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .btn-login.loading .btn-text {
      opacity: 0;
    }

    .btn-login.loading .loading {
      display: block;
    }

    /* Responsive */
    @media (max-width: 480px) {
      .login-card {
        padding: 35px 25px;
      }

      .login-card h3 {
        font-size: 1.5rem;
      }

      .logo {
        width: 75px;
        height: 75px;
        font-size: 35px;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="logo-container">
        <div class="logo">
          <i class="bi bi-boxes"></i>
        </div>
        <h3>ChakaNoks SCMS</h3>
        <p class="subtitle">Supply Chain Management System</p>
      </div>

      <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
          <i class="bi bi-exclamation-circle me-2"></i>
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

      <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
          <i class="bi bi-check-circle me-2"></i>
          <?= session()->getFlashdata('success') ?>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('login/authenticate') ?>" method="post" id="loginForm">
        <?= csrf_field() ?>
        
        <div class="mb-3">
          <label class="form-label">
            <i class="bi bi-person me-1"></i> Username
          </label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-person-circle"></i>
            </span>
            <input 
              type="text" 
              name="username" 
              class="form-control" 
              placeholder="Enter your username" 
              required
              autocomplete="username"
              value="<?= old('username') ?>"
            >
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">
            <i class="bi bi-lock me-1"></i> Password
          </label>
          <div class="password-wrapper">
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-shield-lock"></i>
              </span>
              <input 
                type="password" 
                name="password" 
                id="password" 
                class="form-control" 
                placeholder="Enter your password" 
                required
                autocomplete="current-password"
              >
            </div>
            <i class="bi bi-eye password-toggle" id="togglePassword"></i>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center">
          <div class="form-check">
            <input 
              class="form-check-input" 
              type="checkbox" 
              name="remember_me" 
              id="rememberMe"
            >
            <label class="form-check-label" for="rememberMe">
              Remember me
            </label>
          </div>
          <a href="<?= base_url('forgot-password') ?>" class="forgot-password">
            Forgot Password?
          </a>
        </div>

        <button type="submit" class="btn btn-login text-white mt-4" id="loginBtn">
          <span class="btn-text">
            <i class="bi bi-box-arrow-in-right me-2"></i> Login
          </span>
          <div class="loading">
            <div class="loading-spinner"></div>
          </div>
        </button>
      </form>

      <div class="divider">or</div>

      <div class="text-center">
        <a href="<?= base_url('help') ?>" class="text-decoration-none" style="color: #667eea; font-size: 0.9rem;">
          <i class="bi bi-question-circle me-1"></i> Need help logging in?
        </a>
      </div>

      <p class="footer-text">
        <i class="bi bi-shield-check me-1"></i> 
        Secured by ChakaNoks © <?= date('Y') ?>
      </p>
    </div>
  </div>

  <script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      
      this.classList.toggle('bi-eye');
      this.classList.toggle('bi-eye-slash');
    });

    // Form submission with loading state
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');

    loginForm.addEventListener('submit', function() {
      loginBtn.classList.add('loading');
      loginBtn.disabled = true;
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
      setTimeout(() => {
        alert.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => {
          alert.style.display = 'none';
        }, 300);
      }, 5000);
    });

    // Remember me functionality (store in localStorage)
    const rememberCheckbox = document.getElementById('rememberMe');
    const usernameInput = document.querySelector('input[name="username"]');

    // Load remembered username
    window.addEventListener('DOMContentLoaded', function() {
      const rememberedUsername = localStorage.getItem('remembered_username');
      if (rememberedUsername) {
        usernameInput.value = rememberedUsername;
        rememberCheckbox.checked = true;
      }
    });

    // Save username if remember me is checked
    loginForm.addEventListener('submit', function() {
      if (rememberCheckbox.checked) {
        localStorage.setItem('remembered_username', usernameInput.value);
      } else {
        localStorage.removeItem('remembered_username');
      }
    });
  </script>
</body>
</html>