<?php
require_once '../autoload.php';
require_once '../src/config.php';
require_once '../src/database.php';
require_once '../src/models/user.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$config = include('../src/config.php');
$db = Database::getInstance($config)->pdo();
$userModel = new User($db);
$users = $userModel->getAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>Registered Users</h2>
    <table class="table table-striped">
        <tr><th>ID</th><th>Name</th><th>Email</th></tr>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= $u['first_name'] . ' ' . $u['last_name'] ?></td>
                <td><?= $u['email'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
