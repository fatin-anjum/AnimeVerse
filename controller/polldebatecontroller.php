<?php
require_once __DIR__ . '/../model/polldebatemodel.php';

class PolldebateController {
    private $pdo;
    private $model;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new PolldebateModel($pdo);
    }

    public function handle() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit();
        }

        $message = "";

        // Handle poll creation
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['poll_title'], $_POST['poll_options'])) {
            $title = trim($_POST['poll_title']);
            $desc = trim($_POST['poll_desc'] ?? '');
            $options = array_filter(array_map('trim', $_POST['poll_options']));

            if ($title && count($options) >= 2) {
                $this->model->createPoll($_SESSION['user_id'], $title, $desc, $options);
                $message = "Poll created successfully!";
            } else {
                $message = "Poll must have a title and at least 2 options.";
            }
        }

        // Handle poll voting
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote_option'])) {
            $this->model->votePoll($_SESSION['user_id'], $_POST['vote_option']);
        }

        $polls = $this->model->getAllPolls();
        $model = $this->model; // For view compatibility

        require __DIR__ . '/../view/pollview.php';
    }
}
