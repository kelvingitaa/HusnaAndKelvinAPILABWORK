<?php
session_start();

require_once __DIR__ . '/../src/Config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Models/OTP.php';
require_once __DIR__ . '/../src/Models/User.php';

$config = require __DIR__ . '/../src/Config.php';
$db = Database::getInstance($config)->pdo();
$otpModel = new OTP($db);
$userModel = new User($db);

$errors = [];

if (!isset($_SESSION['pending_user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    $userId = $_SESSION['pending_user_id'];

    // Validate OTP
    if ($otpModel->validate($userId, $code)) {
        unset($_SESSION['pending_user_id']);
        $_SESSION['user_id'] = $userId; // logged in!
        header("Location: dashboard.php");
        exit;
    } else {
        $errors[] = "Invalid or expired OTP";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>2FA Verification</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="col-md-5 mx-auto card p-4 shadow">
    <h3 class="mb-3 text-center">Enter OTP</h3>
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger"><?= implode("<br>", $errors) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label>6-digit Code</label>
        <input type="text" name="code" maxlength="6" class="form-control" required>
      </div>
      <button class="btn btn-success w-100">Verify</button>
    </form>
  </div>
</div>
</body>
</html>
