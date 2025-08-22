<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../model/usermodel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?page=login");
    exit();
}

$user_id = $_SESSION['user_id'];
$userEmail = $_SESSION['user_email'] ?? null;
$model = new UserModel($pdo);

$message = $error = "";


$user = $model->getUserById($user_id);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];

    if ($model->updateProfile($user_id, $username, $email, $bio)) {
        $message = "Profile updated successfully!";
        $user = $model->getUserById($user_id);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];

    $userPasswordRow = $model->getPasswordHash($user_id);

    if ($userPasswordRow && password_verify($current, $userPasswordRow['password_hash'])) {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $model->updatePassword($user_id, $hashed);
        $message = "Password changed successfully!";
    } else {
        $error = "Current password is incorrect.";
    }
}


if (isset($_POST['disband'])) {
    $model->deleteUser($user_id);
    session_destroy();
    header("Location: ../controller/goodbyecontroller.php?disbanded=1");
    exit();
}


require_once __DIR__ . '/../view/profileView.php';

