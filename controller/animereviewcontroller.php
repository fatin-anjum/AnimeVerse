<?php
require_once __DIR__ . '/../model/AnimeReviewModel.php';

class AnimeReviewController {
    private $animeReviewModel;

    public function __construct($pdo) {
        $this->animeReviewModel = new AnimeReviewModel($pdo);
    }

    // Display anime list
    public function index() {
        $anime_list = $this->animeReviewModel->getAllAnime();
        $recent_reviews = $this->animeReviewModel->getRecentReviews(5);
        $top_rated = $this->animeReviewModel->getTopRatedAnime(5);
        
        require __DIR__ . '/../view/animereviewview.php';
    }

    // Display single anime with reviews
    public function viewAnime($anime_id) {
        if (!$anime_id) {
            header("Location: ?page=animereview");
            exit;
        }

        $anime = $this->animeReviewModel->getAnimeById($anime_id);
        if (!$anime) {
            echo "Anime not found.";
            return;
        }

        $reviews = $this->animeReviewModel->getReviewsByAnime($anime_id);
        $rating_stats = $this->animeReviewModel->getAnimeRatingStats($anime_id);
        
        $user_review = null;
        if (isset($_SESSION['user_id'])) {
            $user_review = $this->animeReviewModel->getUserReview($_SESSION['user_id'], $anime_id);
        }

        require __DIR__ . '/../view/singleanimeview.php';
    }

    // Handle review submission
    public function submitReview() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $anime_id = $_POST['anime_id'] ?? null;
            $rating = $_POST['rating'] ?? null;
            $comment = trim($_POST['comment'] ?? '');
            $is_spoiler = isset($_POST['is_spoiler']) ? 1 : 0;
            $spoiler_warning = trim($_POST['spoiler_warning'] ?? '');

            // Validation
            if (!$anime_id || !$rating || $rating < 1 || $rating > 10) {
                $_SESSION['error'] = "Invalid anime ID or rating. Rating must be between 1-10.";
                header("Location: ?page=animereview&action=view&id=$anime_id");
                exit;
            }

            if (empty($comment)) {
                $_SESSION['error'] = "Review comment is required.";
                header("Location: ?page=animereview&action=view&id=$anime_id");
                exit;
            }

            if ($is_spoiler && empty($spoiler_warning)) {
                $_SESSION['error'] = "Spoiler warning is required when marking as spoiler.";
                header("Location: ?page=animereview&action=view&id=$anime_id");
                exit;
            }

            // Check if user already reviewed this anime
            $has_reviewed = $this->animeReviewModel->hasUserReviewed($user_id, $anime_id);
            
            if ($has_reviewed) {
                // Update existing review
                $success = $this->animeReviewModel->updateReview(
                    $user_id, 
                    $anime_id, 
                    $rating, 
                    $comment, 
                    $is_spoiler, 
                    $spoiler_warning
                );
                $_SESSION['success'] = $success ? "Review updated successfully!" : "Failed to update review.";
            } else {
                // Add new review
                $success = $this->animeReviewModel->addAnimeReview(
                    $user_id, 
                    $anime_id, 
                    $rating, 
                    $comment, 
                    $is_spoiler, 
                    $spoiler_warning
                );
                $_SESSION['success'] = $success ? "Review submitted successfully!" : "Failed to submit review.";
            }

            header("Location: ?page=animereview&action=view&id=$anime_id");
            exit;
        }
    }

    // Delete user's review
    public function deleteReview() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        $anime_id = $_GET['anime_id'] ?? null;
        if (!$anime_id) {
            header("Location: ?page=animereview");
            exit;
        }

        $success = $this->animeReviewModel->deleteReview($_SESSION['user_id'], $anime_id);
        $_SESSION['message'] = $success ? "Review deleted successfully!" : "Failed to delete review.";

        header("Location: ?page=animereview&action=view&id=$anime_id");
        exit;
    }

    // Handle different actions
    public function handle() {
        $action = $_GET['action'] ?? 'index';
        $id = $_GET['id'] ?? null;

        switch ($action) {
            case 'index':
                $this->index();
                break;
            case 'view':
                $this->viewAnime($id);
                break;
            case 'submit':
                $this->submitReview();
                break;
            case 'delete':
                $this->deleteReview();
                break;
            default:
                $this->index();
                break;
        }
    }
}
?>