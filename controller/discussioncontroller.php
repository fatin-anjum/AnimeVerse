<?php
require_once __DIR__ . '/../model/genremodel.php';
require_once __DIR__ . '/../model/discussionmodel.php';

class DiscussionController {
    private $pdo;
    private $genreModel;
    private $discussionModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->genreModel = new GenreModel($pdo);
        $this->discussionModel = new DiscussionModel($pdo);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit();
        }

        $genres = $this->genreModel->getAllGenres();
        $selected_genre = isset($_GET['genre_id']) ? intval($_GET['genre_id']) : 0;
        $threads = [];

        if ($selected_genre > 0) {
            $threads = $this->discussionModel->getThreadsByGenre($selected_genre);
        }

        $message = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle thread creation
            if (isset($_POST['title'], $_POST['genre_id'], $_POST['content'])) {
                $title = trim($_POST['title']);
                $content = trim($_POST['content']);
                $genre_id = intval($_POST['genre_id']);
                $user_id = $_SESSION['user_id'];

                if (!empty($title) && !empty($content)) {
                    $this->discussionModel->createThread($genre_id, $user_id, $title, $content);
                    $message = "Thread created successfully!";
                    // Update selected_genre to stay on the same genre page
                    $selected_genre = $genre_id;
                } else {
                    $message = "Please fill in all fields.";
                }
            }

            // Handle likes/dislikes
            if (isset($_POST['like_discussion_id'], $_POST['is_like'])) {
                $discussion_id = intval($_POST['like_discussion_id']);
                $is_like = intval($_POST['is_like']);
                $this->discussionModel->addLikeDislike($discussion_id, $_SESSION['user_id'], $is_like);
                // Preserve genre selection from POST data
                if (isset($_POST['genre_id'])) {
                    $selected_genre = intval($_POST['genre_id']);
                }
            }

            // Handle replies
            if (isset($_POST['reply_discussion_id'], $_POST['reply_content'])) {
                $discussion_id = intval($_POST['reply_discussion_id']);
                $content = trim($_POST['reply_content']);
                if (!empty($content)) {
                    $this->discussionModel->addReply($discussion_id, $_SESSION['user_id'], $content);
                }
                // Preserve genre selection from POST data
                if (isset($_POST['genre_id'])) {
                    $selected_genre = intval($_POST['genre_id']);
                }
            }

            // Refresh threads after any post action
            if ($selected_genre > 0) {
                $threads = $this->discussionModel->getThreadsByGenre($selected_genre);
            }
        }

        $discussionModel = $this->discussionModel; // For view compatibility
        require __DIR__ . '/../view/discussionview.php';
    }

    public function view($id) {
        // Implementation for viewing specific discussion
        $this->index();
    }
}
