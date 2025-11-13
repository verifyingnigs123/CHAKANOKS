<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'SCMS Dashboard') ?></title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: "Poppins", sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
    }

    /* Fixed Navbar */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1050;
      background: linear-gradient(90deg, #ff7043, #ff8a65);
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .navbar-brand {
      font-weight: 700;
      color: #fff !important;
      letter-spacing: 0.5px;
    }

    .logout-btn-footer {
      position: fixed;
      bottom: 0;
      left: 0;
      width: var(--sidebar-width);
      background: linear-gradient(0deg, rgba(255,255,255,0.02), transparent);
      padding: 14px;
      border-top: 1px solid rgba(255,255,255,0.06);
      z-index: 1050;
    }

    .content {
      margin-top: 65px;
      margin-left: 280px;
      padding: 30px;
      min-height: calc(100vh - 65px);
    }

    @media (max-width: 992px) {
      .content {
        margin-left: 0;
        margin-top: 70px;
      }
    }
  </style>
</head>

<body>
  <!-- 🔹 Fixed Top Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?= base_url('dashboard') ?>">
        <i class="fa-solid fa-boxes-stacked"></i> SCMS
      </a>
    </div>
  </nav>

  <!-- 🔹 Include Role-Based Sidebar -->
  <?php include(APPPATH . 'Views/design/sidebar.php'); ?>

  <!-- 🔹 Main Content -->
  <div class="content">
    <?= $this->renderSection('content') ?>
  </div>

  <!-- 🔹 Logout Button Fixed at Bottom -->
  <div class="logout-btn-footer d-none d-lg-block">
    <a href="<?= base_url('auth/logout') ?>" class="btn w-100 text-white fw-bold" style="background: linear-gradient(90deg,#ff5f6d,#ff936a); border:none;">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
