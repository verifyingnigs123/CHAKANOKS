<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login | SCMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
    :root{
      --accent1:#0ea5a4;
      --accent2:#7c3aed;
      --primary-blue: #1e40af;
      --dark-blue: #1e3a8a;
    }
    html,body{min-height:100vh}
    body{
      background: linear-gradient(135deg, #0f172a 0%, rgba(124,58,237,0.08) 40%, rgba(14,165,164,0.06) 100%), url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160"><rect width="160" height="160" fill="none"/></svg>');
      display:flex;align-items:center;justify-content:center;font-family:Inter,system-ui,Segoe UI,Roboto,"Helvetica Neue",Arial;
      padding-top: 80px;
      padding-bottom: 20px;
    }
    
    /* Navigation */
    .navbar {
      background: var(--dark-blue) !important;
      padding: 1.2rem 0;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
    }
    
    .navbar-brand {
      font-weight: 700;
      font-size: 1.4rem;
      color: white !important;
      display: flex;
      align-items: center;
      gap: 0.6rem;
      letter-spacing: -0.5px;
      text-decoration: none;
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
      text-decoration: none;
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
      text-decoration: none;
      display: inline-block;
    }
    
    .btn-login:hover {
      background: #e0e7ff;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .navbar-toggler {
      border: none;
      padding: 0.25rem 0.5rem;
    }
    
    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
    
    @media (max-width: 991px) {
      body {
        padding-top: 70px;
      }
    }
    .login-wrap{max-width:980px;width:95%;display:flex;box-shadow:0 10px 30px rgba(2,6,23,0.4);border-radius:14px;overflow:hidden;background:rgba(255,255,255,0.02);backdrop-filter: blur(6px);}
    .brand-side{flex:1;background:linear-gradient(135deg,var(--accent1),var(--accent2));color:#fff;padding:40px;display:flex;flex-direction:column;align-items:flex-start;justify-content:center;position:relative;overflow:hidden}
    .brand-side h1{font-size:28px;margin:0 0 8px 0;letter-spacing:0.4px;transform:translateY(-6px);opacity:0;animation:slideIn 700ms cubic-bezier(.2,.9,.2,1) forwards 120ms}
    .brand-side p{opacity:0.95;margin-bottom:18px}
    .brand-art{width:100%;max-width:320px;margin-top:18px;transform:translateY(12px);opacity:0;animation:floatIn 900ms ease-out forwards 200ms}
    .brand-art-wrap{position:relative;display:inline-block}
    .brand-glow{position:absolute;right:-40px;top:-40px;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle at 30% 30%, rgba(255,255,255,0.08), rgba(124,58,237,0.02) 40%, transparent 60%);filter: blur(24px);transform:scale(0.9);opacity:0;animation:glow 1400ms ease-out forwards 250ms}
    .form-side{flex:1;background:#fff;padding:36px 32px;}
    .card-title{font-weight:700;margin-bottom:6px}
    .small-desc{color:#6b7280;margin-bottom:18px}
    .password-toggle{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:#6c757d;cursor:pointer;padding:5px 10px;z-index:10}
    .password-wrapper{position:relative}
    .password-wrapper input{padding-right:45px}
    /* logo mark float */
    .logo-mark{transform:translateY(-4px);animation:logoFloat 2200ms ease-in-out infinite}

    /* entrance animations */
    @keyframes floatIn {0%{opacity:0;transform:translateY(22px) scale(.98)}100%{opacity:1;transform:translateY(0) scale(1)}}
    @keyframes slideIn {0%{opacity:0;transform:translateY(8px)}100%{opacity:1;transform:translateY(0)}}
    @keyframes logoFloat {0%{transform:translateY(-3px)}50%{transform:translateY(3px)}100%{transform:translateY(-3px)}}
    @keyframes glow {0%{opacity:0;transform:scale(.85)}100%{opacity:1;transform:scale(1)}}
    @media (max-width:800px){.login-wrap{flex-direction:column}.brand-side{padding:28px;text-align:center;align-items:center}.form-side{padding:24px}}
    .logo-badge{display:inline-flex;align-items:center;gap:10px}
    .logo-mark{width:56px;height:56px;border-radius:10px;background:rgba(255,255,255,0.12);display:inline-flex;align-items:center;justify-content:center}
    .logo-mark svg{width:36px;height:36px}
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
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
            <a class="nav-link active" href="<?= base_url('login') ?>">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

<div class="login-wrap">
  <div class="brand-side">
    <div class="logo-badge">
      <div class="logo-mark" aria-hidden="true">
        <!-- simple inline SVG logo -->
        <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" fill="none">
          <rect x="6" y="18" width="44" height="28" rx="6" fill="white" fill-opacity="0.12" />
          <path d="M18 36v-8l6-4 6 4v8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </div>
      <div>
        <div style="font-weight:700;font-size:16px">ChakaNoks</div>
        <div style="font-size:12px;opacity:0.95">Supply Chain Management</div>
      </div>
    </div>

    <h1>ChakaNoks’ Supply Chain Management System (SCMS)</h1>
    <p>Manage inventory, orders, deliveries, and branches — all in one place. Fast, secure, and built for scale.</p>

    <div class="brand-art-wrap">
      <div class="brand-glow" aria-hidden="true"></div>
      <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='420' height='220' viewBox='0 0 420 220'><defs><linearGradient id='g' x1='0' x2='1'><stop offset='0' stop-color='%23ffffff' stop-opacity='0.12'/><stop offset='1' stop-color='%23ffffff' stop-opacity='0.06'/></linearGradient></defs><rect rx='20' width='420' height='220' fill='url(%23g)' /></svg>" alt="illustration" class="brand-art">
    </div>
  </div>

  <div class="form-side">
    <div class="mb-3">
      <h2 class="card-title">Welcome back</h2>
      <div class="small-desc">Sign in to continue to ChakaNoks’ SCMS</div>
    </div>

    <?php if (session()->getFlashdata('msg')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('msg') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('auth/login') ?>" method="post">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="password-wrapper">
          <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
          <button type="button" class="password-toggle" id="togglePassword" aria-label="Show password">
            <i class="bi bi-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
          <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <div><a href="#" class="small">Forgot?</a></div>
      </div>

      <button type="submit" class="btn btn-primary w-100" style="background:linear-gradient(90deg,var(--accent1),var(--accent2));border:none;padding:12px 16px;font-weight:600">Sign in</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  const eyeIcon = document.getElementById('eyeIcon');
  
  togglePassword && togglePassword.addEventListener('click', function() {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    if (type === 'password') {
      eyeIcon.classList.remove('bi-eye-slash');
      eyeIcon.classList.add('bi-eye');
    } else {
      eyeIcon.classList.remove('bi-eye');
      eyeIcon.classList.add('bi-eye-slash');
    }
  });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
