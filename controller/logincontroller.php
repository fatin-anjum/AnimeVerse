<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../model/UserModel.php';

$error = "";

if (isset($_SESSION['user_id'])) {
 
    header("Location: /Animeverse/index.php?page=home");
    exit();
}

$model = new UserModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $user = $model->getUserByEmail($email);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];

        
        header("Location: /Animeverse/index.php?page=home");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}


require __DIR__ . '/../view/loginview.php';
