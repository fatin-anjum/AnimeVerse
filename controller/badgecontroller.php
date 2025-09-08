<?php
require_once __DIR__ . '/../model/BadgeModel.php';

class BadgeController {
    private $badgeModel;

    public function __construct($pdo) {
        $this->badgeModel = new BadgeModel($pdo);
    }

    // Display user's badges and level progression
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $user_badges = $this->badgeModel->getUserBadges($user_id);
        $level_progress = $this->badgeModel->getLevelProgress($user_id);
        $experience_breakdown = $this->badgeModel->getExperienceBreakdown($user_id);
        $recent_experience = $this->badgeModel->getRecentExperience($user_id, 15);
        $user_stats = $this->badgeModel->getUserStats($user_id);

        require __DIR__ . '/../view/badgeview.php';
    }

    // Display leaderboards
    public function leaderboard() {
        $type = $_GET['type'] ?? 'level';
        $level_leaderboard = $this->badgeModel->getLeaderboard('level', 20);
        $experience_leaderboard = $this->badgeModel->getLeaderboard('experience', 20);

        require __DIR__ . '/../view/leaderboardview.php';
    }

    // Award experience points (DISABLED - automatic only)
    public function awardExperience() {
        // Manual experience awarding is disabled
        $_SESSION['error'] = "Manual experience awarding has been disabled. Experience is now awarded automatically based on your activities.";
        header("Location: ?page=badge");
        exit;
    }

    // Award a badge (DISABLED - automatic only)
    public function awardBadge() {
        // Manual badge awarding is disabled
        $_SESSION['error'] = "Manual badge awarding has been disabled. Badges are now awarded automatically when you meet the requirements.";
        header("Location: ?page=badge");
        exit;
    }

    // Set primary badge
    public function setPrimaryBadge() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $badge_name = $_GET['badge'] ?? null;

        if (!$badge_name) {
            $_SESSION['error'] = "Invalid badge name.";
            header("Location: ?page=badge");
            exit;
        }

        // Check if user has this badge
        if (!$this->badgeModel->hasBadge($user_id, $badge_name)) {
            $_SESSION['error'] = "You don't have this badge.";
            header("Location: ?page=badge");
            exit;
        }

        $success = $this->badgeModel->setPrimaryBadge($user_id, $badge_name);
        
        if ($success) {
            $_SESSION['success'] = "Primary badge updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update primary badge.";
        }

        header("Location: ?page=badge");
        exit;
    }

    // Process actions that award experience
    public function processAction($action, $user_id = null) {
        $user_id = $user_id ?? $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            return false;
        }

        // Award experience for the action
        $success = $this->badgeModel->awardExperienceForAction($user_id, $action);
        
        if ($success) {
            // Check for new badges
            $new_badges = $this->badgeModel->checkForNewBadges($user_id);
            
            // Store new badges in session to show notification
            if (!empty($new_badges)) {
                $_SESSION['new_badges'] = $new_badges;
            }
        }
        
        return $success;
    }

    // Check and display new badge notifications
    public function checkNewBadges() {
        if (isset($_SESSION['new_badges']) && !empty($_SESSION['new_badges'])) {
            $badges = $_SESSION['new_badges'];
            unset($_SESSION['new_badges']);
            
            // Return badges for display
            return $badges;
        }
        
        return [];
    }

    // View other user's badges and progress
    public function viewUserBadges($user_id) {
        if (!$user_id) {
            header("Location: ?page=badge");
            exit;
        }

        // Get basic user info
        $stmt = $this->badgeModel->pdo->prepare("SELECT user_id, username, level, badge, profile_picture FROM users WHERE user_id = ? AND is_active = 1");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header("Location: ?page=badge");
            exit;
        }

        $user_badges = $this->badgeModel->getUserBadges($user_id);
        $level_progress = $this->badgeModel->getLevelProgress($user_id);
        $user_stats = $this->badgeModel->getUserStats($user_id);

        require __DIR__ . '/../view/userbadgesview.php';
    }

    // Get badge requirements/help
    public function getBadgeHelp() {
        $badge_requirements = [
            'Review Badges' => [
                'First Reviewer' => 'Write your first anime review',
                'Review Enthusiast' => 'Write 10 anime reviews',
                'Review Master' => 'Write 50 anime reviews'
            ],
            'Art Badges' => [
                'First Artist' => 'Upload your first fanart',
                'Art Enthusiast' => 'Upload 10 fanarts'
            ],
            'Social Badges' => [
                'Conversation Starter' => 'Start your first discussion',
                'Discussion Leader' => 'Start 20 discussions',
                'Popular User' => 'Gain 10 followers',
                'Community Star' => 'Gain 50 followers'
            ],
            'Achievement Badges' => [
                'Active Member' => 'Earn 500 experience points',
                'Veteran Member' => 'Earn 2000 experience points',
                'Level 5 Achieved' => 'Reach level 5',
                'Level 10 Achieved' => 'Reach level 10'
            ]
        ];

        $experience_guide = [
            'review_submitted' => '20 XP - Submit an anime review',
            'fanart_uploaded' => '15 XP - Upload fanart',
            'discussion_started' => '10 XP - Start a discussion',
            'debate_started' => '10 XP - Start a debate',
            'poll_created' => '8 XP - Create a poll',
            'comment_posted' => '5 XP - Post a comment',
            'profile_updated' => '5 XP - Update your profile',
            'vote_cast' => '2 XP - Cast a vote',
            'login_daily' => '1 XP - Daily login bonus'
        ];

        require __DIR__ . '/../view/badgehelpview.php';
    }

    // Handle different actions
    public function handle() {
        $action = $_GET['action'] ?? 'index';
        $id = $_GET['id'] ?? null;

        switch ($action) {
            case 'index':
                $this->index();
                break;
            case 'leaderboard':
                $this->leaderboard();
                break;
            case 'award_exp':
                $this->awardExperience();
                break;
            case 'award_badge':
                $this->awardBadge();
                break;
            case 'set_primary':
                $this->setPrimaryBadge();
                break;
            case 'view_user':
                $this->viewUserBadges($id);
                break;
            case 'help':
                $this->getBadgeHelp();
                break;
            default:
                $this->index();
                break;
        }
    }
}
?>