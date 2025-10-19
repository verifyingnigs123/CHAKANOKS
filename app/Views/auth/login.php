<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | ChakaNoks SCMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

  <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
    <div class="card-body">
      <h4 class="text-center mb-3">ChakaNoks SCMS</h4>
      <p class="text-center text-muted mb-4">Supply Chain Management System</p>

      <!-- Error or success message -->
      <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>

      <form action="<?= base_url('auth/login') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input 
            type="text" 
            name="username" 
            id="username" 
            class="form-control" 
            placeholder="Enter your username" 
            required
            value="<?= old('username') ?>"
          >
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input 
            type="password" 
            name="password" 
            id="password" 
            class="form-control" 
            placeholder="Enter your password" 
            required
          >
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>

      <p class="text-center text-muted mt-3 mb-0" style="font-size: 0.9rem;">
        &copy; <?= date('Y') ?> ChakaNoks SCMS
      </p>
    </div>
  </div>

</body>
</html>
