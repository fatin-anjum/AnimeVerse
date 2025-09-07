<?php
require_once __DIR__ . '/../model/fanartmodel.php';

class FanartController {
    private $pdo;
    private $model;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new FanArtModel($pdo);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit();
        }

        $message = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete_fanart_id'])) {
                $fanart_id = intval($_POST['delete_fanart_id']);
                try {
                    $this->model->deleteFanArt($fanart_id, $_SESSION['user_id']);
                    $message = "Fan art deleted successfully!";
                } catch (Exception $e) {
                    $message = $e->getMessage();
                }
            }

            if (isset($_POST['title'], $_FILES['fanart_file'])) {
                try {
                    $this->model->uploadFanArt($_SESSION['user_id'], $_POST['title'], $_POST['description'] ?? '', $_FILES['fanart_file']);
                    $message = "Fan art uploaded!";
                } catch (Exception $e) {
                    $message = $e->getMessage();
                }
            }

            if (isset($_POST['heart_fanart_id'])) {
                $this->model->addHeart($_POST['heart_fanart_id'], $_SESSION['user_id']);
            }

            if (isset($_POST['fanart_id'], $_POST['comment'])) {
                $this->model->addComment($_POST['fanart_id'], $_SESSION['user_id'], $_POST['comment']);
            }
        }

        $fanarts = $this->model->getAllFanArt();
        require __DIR__ . '/../view/fanartview.php';
    }
}
