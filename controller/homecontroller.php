<?php
require_once __DIR__ . '/../model/HomeModel.php';

class HomeController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // Check if user is logged in, but don't force redirect for home page
        $userEmail = $_SESSION['user_email'] ?? null;
        
        $model = new HomeModel();
        $inventoryCount = $model->getInventoryCount();
        
        // Load the home view
        require_once __DIR__ . '/../view/homeview.php';
    }
}
