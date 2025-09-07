<?php
require_once __DIR__ . '/../model/UserModel.php';

class RegisterController {
    private $pdo;
    private $model;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new UserModel($pdo);
    }

    public function index() {
        $error = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm'];

            if ($password !== $confirm) {
                $error = "Passwords do not match.";
            } elseif ($this->model->usernameExists($username)) {
                $error = "Username already taken.";
            } elseif ($this->model->emailExists($email)) {
                $error = "Email already registered.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                if ($this->model->registerUser($username, $email, $hash)) {
                    header("Location: ?page=login&registered=1");
                    exit();
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }

        require_once __DIR__ . '/../view/registerView.php';
    }
}
