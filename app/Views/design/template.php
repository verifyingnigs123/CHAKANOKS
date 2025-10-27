<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'FoodChain System') ?></title>
  
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  
  <style>
    body {
      background-color: #f8f9fa;
      font-family: "Poppins", sans-serif;
    }

    .navbar {
      background: linear-gradient(135deg, #ff7043, #ff8a65);
    }

    .navbar-brand {
      font-weight: 600;
      color: #fff !important;
    }

    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      background-color: #343a40;
      color: #fff;
      padding-top: 20px;
      transition: all 0.3s ease;
    }

    .sidebar a {
      color: #adb5bd;
      display: block;
      padding: 12px 20px;
      text-decoration: none;
      transition: 0.3s;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: #ff7043;
      color: #fff;
      border-radius: 8px;
    }

    .content {
      margin-left: 260px;
      padding: 40px 30px;
    }

    footer {
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
      color: #666;
      border-top: 1px solid #ddd;
    }

    @media (max-width: 992px) {
      .sidebar {
        position: static;
        width: 100%;
        height: auto;
      }
      .content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

  <!-- 🔸 Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?= base_url('dashboard') ?>">
        <i class="fa-solid fa-burger"></i> FoodChain System
      </a>
      <div class="d-flex align-items-center">
        <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-light btn-sm">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
      </div>
    </div>
  </nav>

  <!-- 🔸 Include Sidebar -->
  <?php include(APPPATH . 'Views/design/sidebar.php'); ?>

  <!-- 🔸 Page Content -->
  <div class="content">
    <?= $this->renderSection('content') ?>
  </div>

  <footer>
    <p>&copy; <?= date('Y') ?> FoodChain Management System. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
