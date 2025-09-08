<?php
class Collectible {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get all collectibles (default: only available)
    public function getAll($onlyAvailable = true) {
        $sql = "SELECT c.*, u.username FROM collectibles c 
                LEFT JOIN users u ON c.user_id = u.user_id";
        if ($onlyAvailable) {
            $sql .= " WHERE is_sold = 0";
        }
        $sql .= " ORDER BY posted_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get collectible by ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT c.*, u.username FROM collectibles c LEFT JOIN users u ON c.user_id = u.user_id WHERE c.collectible_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add new collectible
    public function add($user_id, $title, $description, $price, $image_url) {
        $stmt = $this->pdo->prepare("INSERT INTO collectibles (user_id, title, description, price, image_url, posted_at) VALUES (?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $title, $description, $price, $image_url]);
    }

    // Mark collectible as sold with buyer info
    public function markAsSold($id, $buyer_name, $buyer_contact, $buyer_location) {
        $stmt = $this->pdo->prepare("
            UPDATE collectibles 
            SET is_sold = 1, buyer_name = ?, buyer_contact = ?, buyer_location = ?
            WHERE collectible_id = ?
        ");
        return $stmt->execute([$buyer_name, $buyer_contact, $buyer_location, $id]);
    }
}
?>
