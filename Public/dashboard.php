<?php
session_start();

// Prevent unauthorized access
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// You can now use $_SESSION['user_id'] to fetch user data
require_once __DIR__ . '/../src/Config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Models/User.php';

$config = require __DIR__ . '/../src/Config.php';
$db = Database::getInstance($config)->pdo();
$userModel = new User($db);
$user = $userModel->findById($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow p-4">
      <h2 class="text-center mb-3">Welcome, <?= htmlspecialchars($user['first_name']); ?> 🎉</h2>
      <p class="text-center">You have successfully logged in with 2FA enabled.</p>
      <div class="d-flex justify-content-center gap-3">
        <a href="users.php" class="btn btn-outline-primary">View All Users</a>
        <a href="goods.php" class="btn btn-outline-success">View Goods & Services</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
      </div>
    </div>
  </div>
</body>
</html>
