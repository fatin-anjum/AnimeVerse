<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../model/UserModel.php';

$error = "";
$model = new UserModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif ($model->usernameExists($username)) {
        $error = "Username already taken.";
    } elseif ($model->emailExists($email)) {
        $error = "Email already registered.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($model->registerUser($username, $email, $hash)) {
            header("Location: ../controller/logincontroller.php?registered=1");
            exit();
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}

require_once __DIR__ . '/../view/registerView.php';
