<?php
session_start();

class GoodbyeController {
    public function index() {
        // Pass variable to view depending on query parameter
        $isDisbanded = isset($_GET['disbanded']);

        // End session if logged in
        if (isset($_SESSION['user_id'])) {
            session_unset();
            session_destroy();
        }

        // Load the view
        include __DIR__ . '/../view/goodbyeView.php';
    }
}

// Run controller
$controller = new GoodbyeController();
$controller->index();
