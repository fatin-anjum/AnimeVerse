<?php
require_once __DIR__ . '/../model/polldebatemodel.php';

class DebateController {
    private $pdo;
    private $model;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new PolldebateModel($pdo);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit();
        }

        $message = "";

        // Handle debate creation
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['debate_title'], $_POST['debate_content'])) {
            $title = trim($_POST['debate_title']);
            $content = trim($_POST['debate_content']);

            // Prevent duplicate debate by same user with same title
            if (!$this->model->debateExists($_SESSION['user_id'], $title)) {
                $this->model->createDebate($_SESSION['user_id'], $title, $content);
                $message = "Debate created successfully!";
            } else {
                $message = "You already created a debate with this title!";
            }
        }

        // Handle debate replies
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['debate_reply_content'], $_POST['debate_id'])) {
            $this->model->addDebateReply($_POST['debate_id'], $_SESSION['user_id'], $_POST['debate_reply_content']);
            $message = "Argument added successfully!";
        }

        // Handle debate voting
        if (isset($_POST['vote_reply_id'], $_POST['vote_type'])) {
            $upvote = $_POST['vote_type'] === 'up';
            $this->model->voteDebateReply($_POST['vote_reply_id'], $_SESSION['user_id'], $upvote);
        }

        $debates = $this->model->getAllDebates();
        $model = $this->model; // For view compatibility

        require __DIR__ . '/../view/debateview.php';
    }
}