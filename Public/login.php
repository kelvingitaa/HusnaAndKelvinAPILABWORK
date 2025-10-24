<?php
session_start();

require_once __DIR__ . '/../src/Config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Models/OTP.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = require __DIR__ . '/../src/Config.php';
$db = Database::getInstance($config)->pdo();
$userModel = new User($db);
$otpModel = new OTP($db);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // 1. Check if user exists
    $user = $userModel->findByEmail($email);
    if (!$user) {
        $errors[] = "Invalid email or password";
    } else {
        // 2. Verify password
        if (password_verify($password, $user['password_hash'])) {
            // 3. Generate OTP
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expires = (new DateTime())->modify('+10 minutes');
            $otpModel->create($user['id'], $code, $expires);

            // 4. Send OTP via email
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // change to your SMTP host
                $mail->SMTPAuth = true;
                $mail->Username = 'your_email@gmail.com'; // your email
                $mail->Password = 'your_email_password'; // your app password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom($config->mail['from'], $config->mail['from_name']);
                $mail->addAddress($user['email'], $user['first_name'] . ' ' . $user['last_name']);
                $mail->isHTML(true);
                $mail->Subject = 'Your SaveEat verification code';
                $mail->Body = "Your 2FA code is: <b>{$code}</b> (expires in 10 minutes)";
                $mail->send();
            } catch (Exception $e) {
                $errors[] = "Could not send OTP: " . $mail->ErrorInfo;
            }

            // 5. Save pending user ID
            $_SESSION['pending_user_id'] = $user['id'];

            // 6. Redirect to 2FA page
            header("Location: verify_2fa.php");
            exit;
        } else {
            $errors[] = "Invalid email or password";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login - SaveEat Lab</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="col-md-5 mx-auto card p-4 shadow">
    <h3 class="mb-3 text-center">Login</h3>
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <?= implode("<br>", $errors) ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" required class="form-control">
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" required class="form-control">
      </div>
      <button class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</div>
</body>
</html>
