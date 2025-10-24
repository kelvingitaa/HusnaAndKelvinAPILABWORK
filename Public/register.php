<?php
// register.php
require_once __DIR__ . '/../src/Config.php';
$config = require __DIR__ . '/../src/Config.php';
$db = Database::getInstance($config)->pdo();
$userModel = new User($db);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitize
    $first = Validator::sanitize($_POST['first_name'] ?? '');
    $last  = Validator::sanitize($_POST['last_name'] ?? '');
    $email = Validator::sanitize($_POST['email'] ?? '');
    $phone = Validator::sanitize($_POST['phone'] ?? '');
    $pwd   = $_POST['password'] ?? '';
    $pwd2  = $_POST['password_confirm'] ?? '';

    if (!Validator::required($first)) $errors[] = "First name required";
    if (!Validator::required($last)) $errors[] = "Last name required";
    if (!Validator::email($email)) $errors[] = "Invalid email";
    if (!Validator::minLen($pwd, 8)) $errors[] = "Password min 8 chars";
    if ($pwd !== $pwd2) $errors[] = "Passwords do not match";

    // unique email
    if ($userModel->findByEmail($email)) $errors[] = "Email already registered";

    if (empty($errors)) {
        $id = $userModel->create([
            'first_name'=>$first,
            'last_name'=>$last,
            'email'=>$email,
            'phone'=>$phone,
            'password'=>$pwd
        ]);
        header("Location: login.php?registered=1");
        exit;
    }
}
?>
<!-- Then render form (Bootstrap 5) with client-side JS validation -->
