<?php
class HomeModel {
    public function getUserEmail() {
        return $_SESSION['email'] ?? null;
    }

    // Example method if you want to fetch inventory count or other data
    public function getInventoryCount() {
        // Pretend you query DB for inventory count
        return 150; 
    }
}
