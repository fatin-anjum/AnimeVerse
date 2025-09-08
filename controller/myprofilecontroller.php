<?php
class MyProfileController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        // Load models
        require_once __DIR__ . "/../model/UserModel.php";
        require_once __DIR__ . "/../model/CollectibleModel.php";
        require_once __DIR__ . "/../model/FanartModel.php";
        require_once __DIR__ . "/../model/FollowModel.php";
        require_once __DIR__ . "/../model/BadgeModel.php";

        $userModel = new UserModel($this->pdo);
        $collectibleModel = new Collectible($this->pdo);
        $fanartModel = new FanartModel($this->pdo);
        $followModel = new FollowModel($this->pdo);
        $badgeModel = new BadgeModel($this->pdo);

        $user_id = $_SESSION['user_id'];
        $tab = $_GET['tab'] ?? 'overview'; // Default tab

        // Get user data
        $user = $userModel->getUserById($user_id);
        
        // Get follow stats
        $follow_stats = $followModel->getFollowStats($user_id);
        $followers = $follow_stats['followers'];
        $following = $follow_stats['following'];
        
        // Get user badges
        $user_badges = $badgeModel->getUserBadges($user_id);
        $badge_count = count($user_badges);
        
        // Get level progress
        $level_progress = $badgeModel->getLevelProgress($user_id);
        
        // Get user statistics
        $user_stats = $badgeModel->getUserStats($user_id);
        if (empty($user_stats)) {
            $user_stats = ['total_xp' => $level_progress['total_experience'] ?? 0];
        } else {
            $user_stats['total_xp'] = $level_progress['total_experience'] ?? 0;
        }

        // Get collectibles (if methods exist)
        $collectibles = method_exists($collectibleModel, 'getByUser') 
            ? $collectibleModel->getByUser($user_id) 
            : [];
        $soldCollectibles = method_exists($collectibleModel, 'getSoldByUser') 
            ? $collectibleModel->getSoldByUser($user_id) 
            : [];
            
        // Get fanart
        $fanart = method_exists($fanartModel, 'getByUser') 
            ? $fanartModel->getByUser($user_id) 
            : [];

        // Ensure all required variables are set for the view
        $tab = $tab ?: 'overview';
        $user = $user ?: ['username' => 'Unknown', 'profile_picture' => '', 'badge' => 'None'];
        $followers = $followers ?: 0;
        $following = $following ?: 0;
        $collectibles = $collectibles ?: [];
        $soldCollectibles = $soldCollectibles ?: [];
        $fanart = $fanart ?: [];
        $user_badges = $user_badges ?: [];
        $level_progress = $level_progress ?: [
            'current_level' => 1, 
            'total_experience' => 0, 
            'next_level_exp' => 100, 
            'progress_percentage' => 0
        ];
        $user_stats = $user_stats ?: [];

        require __DIR__ . "/../view/myprofileview.php";
    }

    public function handle() {
        $this->index();
    }
}
