<?php

class LogoutController {
    public function __construct($pdo) {
        // PDO not needed for logout, but maintaining consistency
    }
    
    public function index() {
        // Clear all session variables
        session_unset();
        
        // Destroy the session
        session_destroy();
        
        // Redirect to login page
        header("Location: index.php?page=login");
        exit();
    }
}
