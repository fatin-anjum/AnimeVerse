<?php
require_once __DIR__ . '/../model/usermodel.php';

class ProfileController {
    private $pdo;
    private $model;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new UserModel($pdo);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $userEmail = $_SESSION['user_email'] ?? null;
        
        $message = $error = "";
        $user = $this->model->getUserById($user_id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $bio = $_POST['bio'];

            if ($this->model->updateProfile($user_id, $username, $email, $bio)) {
                $message = "Profile updated successfully!";
                $user = $this->model->getUserById($user_id);
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
            $current = $_POST['current_password'];
            $new = $_POST['new_password'];

            $userPasswordRow = $this->model->getPasswordHash($user_id);

            if ($userPasswordRow && password_verify($current, $userPasswordRow['password_hash'])) {
                $hashed = password_hash($new, PASSWORD_DEFAULT);
                $this->model->updatePassword($user_id, $hashed);
                $message = "Password changed successfully!";
            } else {
                $error = "Current password is incorrect.";
            }
        }

        if (isset($_POST['disband'])) {
            $this->model->deleteUser($user_id);
            session_destroy();
            header("Location: ?page=goodbye&disbanded=1");
            exit();
        }

        require_once __DIR__ . '/../view/profileView.php';
    }
}

