<?php
require_once '../autoload.php';
require_once '../src/config.php';
require_once '../src/database.php';
require_once '../src/models/goods.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$config = include('../src/config.php');
$db = Database::getInstance($config)->pdo();
$goodsModel = new Goods($db);
$goods = $goodsModel->getAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Goods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>Goods and Services</h2>
    <table class="table table-bordered">
        <tr><th>ID</th><th>Name</th><th>Price</th><th>Description</th></tr>
        <?php foreach ($goods as $g): ?>
            <tr>
                <td><?= $g['id'] ?></td>
                <td><?= $g['name'] ?></td>
                <td><?= $g['price'] ?></td>
                <td><?= $g['description'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
