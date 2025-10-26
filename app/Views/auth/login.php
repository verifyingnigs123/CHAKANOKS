<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | SCMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</div>

</body>
</html>
