<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../model/fanartmodel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?page=login");
    exit();
}

$model = new FanArtModel($pdo);
$message = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_fanart_id'])) {
        $fanart_id = intval($_POST['delete_fanart_id']);
        try {
            $model->deleteFanArt($fanart_id, $_SESSION['user_id']);
            $message = "Fan art deleted successfully!";
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }

    if (isset($_POST['title'], $_FILES['fanart_file'])) {
        try {
            $model->uploadFanArt($_SESSION['user_id'], $_POST['title'], $_POST['description'] ?? '', $_FILES['fanart_file']);
            $message = "Fan art uploaded!";
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }

    if (isset($_POST['heart_fanart_id'])) {
        $model->addHeart($_POST['heart_fanart_id'], $_SESSION['user_id']);
    }

    if (isset($_POST['fanart_id'], $_POST['comment'])) {
        $model->addComment($_POST['fanart_id'], $_SESSION['user_id'], $_POST['comment']);
    }
}

$fanarts = $model->getAllFanArt();
require __DIR__ . '/../view/fanartview.php';
