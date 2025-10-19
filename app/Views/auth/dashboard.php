<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SCMS Dashboard</title>
</head>
<body>
    <h2>Welcome, <?= esc($username) ?>!</h2>
    <p>Role: <?= esc($role) ?></p>

    <a href="<?= base_url('auth/logout') ?>">Logout</a>
</body>
</html>
