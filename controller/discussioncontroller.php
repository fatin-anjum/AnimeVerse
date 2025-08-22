<?php
session_start();

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../model/genremodel.php';
require_once __DIR__ . '/../model/discussionmodel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?page=login");
    exit();
}


$genreModel = new GenreModel($pdo);
$discussionModel = new DiscussionModel($pdo);


$genres = $genreModel->getAllGenres();
$selected_genre = isset($_GET['genre_id']) ? intval($_GET['genre_id']) : 0;


$threads = [];


if ($selected_genre > 0) {
    $threads = $discussionModel->getThreadsByGenre($selected_genre);
}


$message = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    if (isset($_POST['title'], $_POST['genre_id'], $_POST['content'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $genre_id = intval($_POST['genre_id']);
        $user_id = $_SESSION['user_id'];

        if (!empty($title) && !empty($content)) {
            $discussionModel->createThread($genre_id, $user_id, $title, $content);
            $message = "Thread created successfully!";
        } else {
            $message = "Please fill in all fields.";
        }
    }

    
    if (isset($_POST['like_discussion_id'], $_POST['is_like'])) {
        $discussion_id = intval($_POST['like_discussion_id']);
        $is_like = intval($_POST['is_like']);
        $discussionModel->addLikeDislike($discussion_id, $_SESSION['user_id'], $is_like);
    }

    
    if (isset($_POST['reply_discussion_id'], $_POST['reply_content'])) {
        $discussion_id = intval($_POST['reply_discussion_id']);
        $content = trim($_POST['reply_content']);
        if (!empty($content)) {
            $discussionModel->addReply($discussion_id, $_SESSION['user_id'], $content);
        }
    }


    if ($selected_genre > 0) {
        $threads = $discussionModel->getThreadsByGenre($selected_genre);
    }
}

require __DIR__ . '/../view/discussionview.php';
