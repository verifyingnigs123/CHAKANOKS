<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login | SCMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
    :root{--accent1:#0ea5a4;--accent2:#7c3aed}
    html,body{height:100%}
    body{
      background: linear-gradient(135deg, #0f172a 0%, rgba(124,58,237,0.08) 40%, rgba(14,165,164,0.06) 100%), url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160"><rect width="160" height="160" fill="none"/></svg>');
      display:flex;align-items:center;justify-content:center;font-family:Inter,system-ui,Segoe UI,Roboto,"Helvetica Neue",Arial;
    }
    .login-wrap{max-width:980px;width:95%;display:flex;box-shadow:0 10px 30px rgba(2,6,23,0.4);border-radius:14px;overflow:hidden;background:rgba(255,255,255,0.02);backdrop-filter: blur(6px);}
    .brand-side{flex:1;background:linear-gradient(135deg,var(--accent1),var(--accent2));color:#fff;padding:40px;display:flex;flex-direction:column;align-items:flex-start;justify-content:center}
    .brand-side h1{font-size:28px;margin:0 0 8px 0;letter-spacing:0.4px}
    .brand-side p{opacity:0.95;margin-bottom:18px}
    .brand-art{width:100%;max-width:260px;margin-top:10px}
    .form-side{flex:1;background:#fff;padding:36px 32px;}
    .card-title{font-weight:700;margin-bottom:6px}
    .small-desc{color:#6b7280;margin-bottom:18px}
    .password-toggle{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:#6c757d;cursor:pointer;padding:5px 10px;z-index:10}
    .password-wrapper{position:relative}
    .password-wrapper input{padding-right:45px}
    @media (max-width:800px){.login-wrap{flex-direction:column}.brand-side{padding:28px;text-align:center;align-items:center}.form-side{padding:24px}}
    .logo-badge{display:inline-flex;align-items:center;gap:10px}
    .logo-mark{width:56px;height:56px;border-radius:10px;background:rgba(255,255,255,0.12);display:inline-flex;align-items:center;justify-content:center}
    .logo-mark svg{width:36px;height:36px}
  </style>
</head>
<body>

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

    <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='420' height='220' viewBox='0 0 420 220'><defs><linearGradient id='g' x1='0' x2='1'><stop offset='0' stop-color='%23ffffff' stop-opacity='0.12'/><stop offset='1' stop-color='%23ffffff' stop-opacity='0.06'/></linearGradient></defs><rect rx='20' width='420' height='220' fill='url(%23g)' /></svg>" alt="illustration" class="brand-art">
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
        <input type="email" name="email" class="form-control" placeholder="you@company.com" required>
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

</body>
</html>
