<?php
require_once __DIR__ . '/../model/FollowModel.php';

class FollowController {
    private $followModel;

    public function __construct($pdo) {
        $this->followModel = new FollowModel($pdo);
    }

    // Display follow dashboard
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $suggested_users = $this->followModel->getSuggestedUsers($user_id, 8);
        $popular_users = $this->followModel->getPopularUsers(6);
        $recent_activity = $this->followModel->getFollowingActivity($user_id, 15);
        $follow_stats = $this->followModel->getFollowStats($user_id);

        require __DIR__ . '/../view/followview.php';
    }

    // Follow a user
    public function followUser() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        $follower_id = $_SESSION['user_id'];
        $followee_id = $_GET['user_id'] ?? null;
        $redirect_url = $_GET['redirect'] ?? '?page=follow';

        if (!$followee_id) {
            $_SESSION['error'] = "Invalid user ID.";
            header("Location: $redirect_url");
            exit;
        }

        if ($follower_id == $followee_id) {
            $_SESSION['error'] = "You cannot follow yourself.";
            header("Location: $redirect_url");
            exit;
        }

        $success = $this->followModel->followUser($follower_id, $followee_id);
        
        if ($success) {
            $_SESSION['success'] = "User followed successfully!";
        } else {
            $_SESSION['error'] = "Failed to follow user. You may already be following them.";
        }

        header("Location: $redirect_url");
        exit;
    }

    // Unfollow a user
    public function unfollowUser() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        $follower_id = $_SESSION['user_id'];
        $followee_id = $_GET['user_id'] ?? null;
        $redirect_url = $_GET['redirect'] ?? '?page=follow';

        if (!$followee_id) {
            $_SESSION['error'] = "Invalid user ID.";
            header("Location: $redirect_url");
            exit;
        }

        $success = $this->followModel->unfollowUser($follower_id, $followee_id);
        
        if ($success) {
            $_SESSION['success'] = "User unfollowed successfully!";
        } else {
            $_SESSION['error'] = "Failed to unfollow user.";
        }

        header("Location: $redirect_url");
        exit;
    }

    // Display followers list
    public function viewFollowers($user_id = null) {
        if (!$user_id) {
            $user_id = $_SESSION['user_id'] ?? null;
        }

        if (!$user_id) {
            header("Location: ?page=login");
            exit;
        }

        $followers = $this->followModel->getFollowers($user_id);
        $current_user_id = $_SESSION['user_id'] ?? null;
        
        // Check follow status for each follower if current user is logged in
        if ($current_user_id) {
            foreach ($followers as &$follower) {
                $follower['is_following'] = $this->followModel->isFollowing($current_user_id, $follower['user_id']);
            }
        }

        require __DIR__ . '/../view/followersview.php';
    }

    // Display following list
    public function viewFollowing($user_id = null) {
        if (!$user_id) {
            $user_id = $_SESSION['user_id'] ?? null;
        }

        if (!$user_id) {
            header("Location: ?page=login");
            exit;
        }

        $following = $this->followModel->getFollowing($user_id);
        $current_user_id = $_SESSION['user_id'] ?? null;

        require __DIR__ . '/../view/followingview.php';
    }

    // Search users
    public function searchUsers() {
        $query = $_GET['q'] ?? '';
        $current_user_id = $_SESSION['user_id'] ?? null;

        if (empty($query)) {
            $_SESSION['error'] = "Please enter a search term.";
            header("Location: ?page=follow");
            exit;
        }

        $search_results = $this->followModel->searchUsers($query, $current_user_id);
        
        require __DIR__ . '/../view/usersearchview.php';
    }

    // View user profile with follow option
    public function viewProfile($user_id) {
        if (!$user_id) {
            header("Location: ?page=follow");
            exit;
        }

        // Get user details from UserModel would be ideal, but we'll get basic info
        $stmt = $this->followModel->getPDO()->prepare("SELECT * FROM users WHERE user_id = ? AND is_active = 1");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header("Location: ?page=follow");
            exit;
        }

        $current_user_id = $_SESSION['user_id'] ?? null;
        $is_following = false;
        $follow_stats = $this->followModel->getFollowStats($user_id);

        if ($current_user_id) {
            $is_following = $this->followModel->isFollowing($current_user_id, $user_id);
        }

        // Get user's recent activity
        $user_activity = $this->getUserActivity($user_id);
        
        // Get user badges
        require_once __DIR__ . '/../model/badgemodel.php';
        $badgeModel = new BadgeModel($this->followModel->getPDO());
        $user_badges = $badgeModel->getUserBadges($user_id);
        
        // Make PDO available for the view
        $pdo = $this->followModel->getPDO();

        require __DIR__ . '/../view/userprofileview.php';
    }

    // Get mutual follows
    public function viewMutualFollows() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $mutual_follows = $this->followModel->getMutualFollows($user_id);

        require __DIR__ . '/../view/mutualfollowsview.php';
    }

    // AJAX endpoint for follow/unfollow
    public function toggleFollow() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $followee_id = $input['user_id'] ?? null;
        $action = $input['action'] ?? null; // 'follow' or 'unfollow'

        if (!$followee_id || !in_array($action, ['follow', 'unfollow'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid parameters']);
            exit;
        }

        $follower_id = $_SESSION['user_id'];

        if ($follower_id == $followee_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Cannot follow yourself']);
            exit;
        }

        $success = false;
        if ($action === 'follow') {
            $success = $this->followModel->followUser($follower_id, $followee_id);
        } else {
            $success = $this->followModel->unfollowUser($follower_id, $followee_id);
        }

        if ($success) {
            $follower_count = $this->followModel->getFollowerCount($followee_id);
            echo json_encode([
                'success' => true,
                'action' => $action,
                'new_count' => $follower_count
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Operation failed']);
        }
        exit;
    }

    // Helper method to get user activity
    private function getUserActivity($user_id, $limit = 10) {
        // Ensure limit is an integer to prevent SQL injection
        $limit = (int) $limit;
        $stmt = $this->followModel->getPDO()->prepare("
            (SELECT 'fanart' as type, fa.fanart_id as content_id, fa.title, fa.created_at
             FROM fanart fa WHERE fa.user_id = ? ORDER BY fa.created_at DESC LIMIT 5)
            UNION ALL
            (SELECT 'review' as type, r.review_id as content_id, 
                    CONCAT('Review: ', a.title) as title, r.reviewed_at as created_at
             FROM reviews r 
             JOIN anime a ON r.anime_id = a.anime_id
             WHERE r.user_id = ? ORDER BY r.reviewed_at DESC LIMIT 5)
            UNION ALL
            (SELECT 'discussion' as type, gd.discussion_id as content_id, gd.title, gd.posted_at as created_at
             FROM genre_discussions gd WHERE gd.user_id = ? ORDER BY gd.posted_at DESC LIMIT 5)
            ORDER BY created_at DESC LIMIT $limit
        ");
        $stmt->execute([$user_id, $user_id, $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Handle different actions
    public function handle() {
        $action = $_GET['action'] ?? 'index';
        $id = $_GET['id'] ?? null;

        switch ($action) {
            case 'index':
                $this->index();
                break;
            case 'follow':
                $this->followUser();
                break;
            case 'unfollow':
                $this->unfollowUser();
                break;
            case 'followers':
                $this->viewFollowers($id);
                break;
            case 'following':
                $this->viewFollowing($id);
                break;
            case 'search':
                $this->searchUsers();
                break;
            case 'profile':
                $this->viewProfile($id);
                break;
            case 'mutual':
                $this->viewMutualFollows();
                break;
            case 'toggle':
                $this->toggleFollow();
                break;
            default:
                $this->index();
                break;
        }
    }
}
?>