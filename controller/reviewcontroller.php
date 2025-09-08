<?php
require_once __DIR__ . '/../model/ReviewModel.php';

class ReviewController {
    private $reviewModel;

    public function __construct($pdo) {
        $this->reviewModel = new ReviewModel($pdo);
    }

    // Submit a review
    public function submit() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $item_id = $_POST['item_id'];
            $rating = $_POST['rating'];
            $comment = $_POST['comment'];

            $this->reviewModel->addReview($user_id, $item_id, $rating, $comment);
            header("Location: ?page=item&id=$item_id");
            exit;
        }
    }

    // Display reviews for an item
    public function display($item_id) {
        $reviews = $this->reviewModel->getReviewsByItem($item_id);
        $avgData = $this->reviewModel->getAverageRating($item_id);

        require __DIR__ . '/../view/reviews_view.php';
    }
}
