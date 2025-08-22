<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../model/polldebatemodel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?page=login");
    exit();
}

$model = new PolldebateModel($pdo);
$message = "";


$activeTab = $_POST['active_tab'] ?? $_GET['tab'] ?? 'poll';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['poll_title'], $_POST['poll_options']) && $activeTab=='poll') {
    $title = trim($_POST['poll_title']);
    $desc = trim($_POST['poll_desc'] ?? '');
    $options = array_filter(array_map('trim', $_POST['poll_options']));

    if ($title && count($options) >= 2) {
        $model->createPoll($_SESSION['user_id'], $title, $desc, $options);
        $message = "Poll created successfully!";
    } else {
        $message = "Poll must have a title and at least 2 options.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote_option']) && $activeTab=='poll') {
    $model->votePoll($_SESSION['user_id'], $_POST['vote_option']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['debate_title'], $_POST['debate_content']) && $activeTab=='debate') {
    $title = trim($_POST['debate_title']);
    $content = trim($_POST['debate_content']);

    // Prevent duplicate debate by same user with same title
    if (!$model->debateExists($_SESSION['user_id'], $title)) {
        $model->createDebate($_SESSION['user_id'], $title, $content);
        $message = "Debate created!";
    } else {
        $message = "You already created a debate with this title!";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['debate_reply_content'], $_POST['debate_id']) && $activeTab=='debate') {
    $model->addDebateReply($_POST['debate_id'], $_SESSION['user_id'], $_POST['debate_reply_content']);
}



if (isset($_POST['vote_reply_id'], $_POST['vote_type'])) {
    $upvote = $_POST['vote_type'] === 'up';
    $model->voteDebateReply($_POST['vote_reply_id'], $_SESSION['user_id'], $upvote);
    $activeTab = 'debate'; // keep user on debate tab
}




$polls = $model->getAllPolls();
$debates = $model->getAllDebates();


require __DIR__ . '/../view/polldebateview.php';
