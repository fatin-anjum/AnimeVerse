<?php
class ReviewModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Add review
    public function addReview($user_id, $item_id, $rating, $comment) {
        $stmt = $this->pdo->prepare("INSERT INTO reviews (user_id, item_id, rating, comment) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $item_id, $rating, $comment]);
    }

    // Get reviews for an item
    public function getReviewsByItem($item_id) {
        $stmt = $this->pdo->prepare("SELECT r.*, u.username, u.profile_picture FROM reviews r JOIN users u ON r.user_id = u.user_id WHERE item_id = ? ORDER BY created_at DESC");
        $stmt->execute([$item_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get average rating for an item
    public function getAverageRating($item_id) {
        $stmt = $this->pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE item_id = ?");
        $stmt->execute([$item_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
