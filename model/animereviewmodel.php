<?php
class AnimeReviewModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Add a review for an anime
    public function addAnimeReview($user_id, $anime_id, $rating, $comment, $is_spoiler = 0, $spoiler_warning = null) {
        $stmt = $this->pdo->prepare("INSERT INTO reviews (user_id, anime_id, rating, comment, is_spoiler, spoiler_warning) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$user_id, $anime_id, $rating, $comment, $is_spoiler, $spoiler_warning]);
    }

    // Get all reviews for a specific anime
    public function getReviewsByAnime($anime_id) {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.username, u.profile_picture, u.level, u.badge 
            FROM reviews r 
            JOIN users u ON r.user_id = u.user_id 
            WHERE r.anime_id = ? 
            ORDER BY r.reviewed_at DESC
        ");
        $stmt->execute([$anime_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get average rating and total reviews for an anime
    public function getAnimeRatingStats($anime_id) {
        $stmt = $this->pdo->prepare("
            SELECT 
                AVG(rating) as avg_rating, 
                COUNT(*) as total_reviews,
                COUNT(CASE WHEN rating = 1 THEN 1 END) as rating_1,
                COUNT(CASE WHEN rating = 2 THEN 1 END) as rating_2,
                COUNT(CASE WHEN rating = 3 THEN 1 END) as rating_3,
                COUNT(CASE WHEN rating = 4 THEN 1 END) as rating_4,
                COUNT(CASE WHEN rating = 5 THEN 1 END) as rating_5,
                COUNT(CASE WHEN rating = 6 THEN 1 END) as rating_6,
                COUNT(CASE WHEN rating = 7 THEN 1 END) as rating_7,
                COUNT(CASE WHEN rating = 8 THEN 1 END) as rating_8,
                COUNT(CASE WHEN rating = 9 THEN 1 END) as rating_9,
                COUNT(CASE WHEN rating = 10 THEN 1 END) as rating_10
            FROM reviews 
            WHERE anime_id = ?
        ");
        $stmt->execute([$anime_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get anime details
    public function getAnimeById($anime_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM anime WHERE anime_id = ?");
        $stmt->execute([$anime_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all anime for listing
    public function getAllAnime() {
        $stmt = $this->pdo->prepare("
            SELECT a.*, 
                   AVG(r.rating) as avg_rating, 
                   COUNT(r.review_id) as review_count
            FROM anime a 
            LEFT JOIN reviews r ON a.anime_id = r.anime_id 
            GROUP BY a.anime_id 
            ORDER BY a.title ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Check if user has already reviewed this anime
    public function hasUserReviewed($user_id, $anime_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reviews WHERE user_id = ? AND anime_id = ?");
        $stmt->execute([$user_id, $anime_id]);
        return $stmt->fetchColumn() > 0;
    }

    // Update user's existing review
    public function updateReview($user_id, $anime_id, $rating, $comment, $is_spoiler = 0, $spoiler_warning = null) {
        $stmt = $this->pdo->prepare("
            UPDATE reviews 
            SET rating = ?, comment = ?, is_spoiler = ?, spoiler_warning = ?, reviewed_at = NOW() 
            WHERE user_id = ? AND anime_id = ?
        ");
        return $stmt->execute([$rating, $comment, $is_spoiler, $spoiler_warning, $user_id, $anime_id]);
    }

    // Delete a review
    public function deleteReview($user_id, $anime_id) {
        $stmt = $this->pdo->prepare("DELETE FROM reviews WHERE user_id = ? AND anime_id = ?");
        return $stmt->execute([$user_id, $anime_id]);
    }

    // Get user's review for a specific anime
    public function getUserReview($user_id, $anime_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM reviews WHERE user_id = ? AND anime_id = ?");
        $stmt->execute([$user_id, $anime_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get recent reviews across all anime
    public function getRecentReviews($limit = 10) {
        // Ensure limit is an integer to prevent SQL injection
        $limit = (int) $limit;
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.username, u.profile_picture, a.title as anime_title 
            FROM reviews r 
            JOIN users u ON r.user_id = u.user_id 
            JOIN anime a ON r.anime_id = a.anime_id 
            ORDER BY r.reviewed_at DESC 
            LIMIT $limit
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get top rated anime
    public function getTopRatedAnime($limit = 10) {
        // Ensure limit is an integer to prevent SQL injection
        $limit = (int) $limit;
        $stmt = $this->pdo->prepare("
            SELECT a.*, AVG(r.rating) as avg_rating, COUNT(r.review_id) as review_count
            FROM anime a 
            JOIN reviews r ON a.anime_id = r.anime_id 
            GROUP BY a.anime_id 
            HAVING COUNT(r.review_id) >= 3
            ORDER BY avg_rating DESC, review_count DESC 
            LIMIT $limit
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>