<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | SCMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
    .password-toggle {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #6c757d;
      cursor: pointer;
      padding: 5px 10px;
      z-index: 10;
    }
    .password-toggle:hover {
      color: #495057;
    }
    .password-wrapper {
      position: relative;
    }
    .password-wrapper input {
      padding-right: 45px;
    }
  </style>
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="card shadow p-4 col-md-4 mx-auto">
    <h3 class="text-center mb-4">Login</h3>

    <?php if (session()->getFlashdata('msg')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('msg') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('auth/login') ?>" method="post">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <div class="password-wrapper">
          <input type="password" name="password" id="password" class="form-control" required>
          <button type="button" class="password-toggle" id="togglePassword" aria-label="Show password">
            <i class="bi bi-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  const eyeIcon = document.getElementById('eyeIcon');
  
  togglePassword.addEventListener('click', function() {
    // Toggle password visibility
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    // Toggle eye icon
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
