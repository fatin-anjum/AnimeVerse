<?php
class BadgeModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Add experience points to a user
    public function addExperience($user_id, $action_type, $points, $description = null) {
        $stmt = $this->pdo->prepare("
            INSERT INTO user_experience (user_id, action_type, points_earned, description) 
            VALUES (?, ?, ?, ?)
        ");
        
        $success = $stmt->execute([$user_id, $action_type, $points, $description]);
        
        if ($success) {
            $this->updateUserLevel($user_id);
            $this->checkForNewBadges($user_id);
        }
        
        return $success;
    }

    // Get total experience points for a user
    public function getTotalExperience($user_id) {
        $stmt = $this->pdo->prepare("SELECT SUM(points_earned) FROM user_experience WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn() ?: 0;
    }

    // Calculate level based on experience points
    public function calculateLevel($experience_points) {
        // Level progression: Level 1 = 0-99 XP, Level 2 = 100-299 XP, Level 3 = 300-599 XP, etc.
        // Formula: Level = floor(sqrt(experience / 100)) + 1
        if ($experience_points < 100) return 1;
        
        $level = floor(sqrt($experience_points / 100)) + 1;
        return min($level, 100); // Cap at level 100
    }

    // Update user's level based on experience
    public function updateUserLevel($user_id) {
        $total_exp = $this->getTotalExperience($user_id);
        $new_level = $this->calculateLevel($total_exp);
        
        $stmt = $this->pdo->prepare("UPDATE users SET level = ? WHERE user_id = ?");
        return $stmt->execute([$new_level, $user_id]);
    }

    // Award a badge to a user
    public function awardBadge($user_id, $badge_name, $badge_description, $badge_icon = null) {
        // Check if user already has this badge
        if ($this->hasBadge($user_id, $badge_name)) {
            return false;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO user_badges (user_id, badge_name, badge_description, badge_icon) 
            VALUES (?, ?, ?, ?)
        ");
        
        $success = $stmt->execute([$user_id, $badge_name, $badge_description, $badge_icon]);
        
        // Update user's primary badge if it's their first badge
        if ($success) {
            $badge_count = $this->getUserBadgeCount($user_id);
            if ($badge_count === 1) {
                $this->setPrimaryBadge($user_id, $badge_name);
            }
        }
        
        return $success;
    }

    // Check if user has a specific badge
    public function hasBadge($user_id, $badge_name) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM user_badges WHERE user_id = ? AND badge_name = ?");
        $stmt->execute([$user_id, $badge_name]);
        return $stmt->fetchColumn() > 0;
    }

    // Get all badges for a user
    public function getUserBadges($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM user_badges 
            WHERE user_id = ? 
            ORDER BY earned_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get badge count for a user
    public function getUserBadgeCount($user_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM user_badges WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }

    // Set primary badge for a user (displayed on their profile)
    public function setPrimaryBadge($user_id, $badge_name) {
        $stmt = $this->pdo->prepare("UPDATE users SET badge = ? WHERE user_id = ?");
        return $stmt->execute([$badge_name, $user_id]);
    }

    // Check for new badges based on user activity
    public function checkForNewBadges($user_id) {
        $badges_awarded = [];
        
        // Get user stats
        $stats = $this->getUserStats($user_id);
        
        // Review badges
        if ($stats['review_count'] >= 1 && !$this->hasBadge($user_id, 'First Reviewer')) {
            $this->awardBadge($user_id, 'First Reviewer', 'Wrote your first anime review', '📝');
            $badges_awarded[] = 'First Reviewer';
        }
        
        if ($stats['review_count'] >= 10 && !$this->hasBadge($user_id, 'Review Enthusiast')) {
            $this->awardBadge($user_id, 'Review Enthusiast', 'Wrote 10 anime reviews', '✍️');
            $badges_awarded[] = 'Review Enthusiast';
        }
        
        if ($stats['review_count'] >= 50 && !$this->hasBadge($user_id, 'Review Master')) {
            $this->awardBadge($user_id, 'Review Master', 'Wrote 50 anime reviews', '🏆');
            $badges_awarded[] = 'Review Master';
        }
        
        // Fanart badges
        if ($stats['fanart_count'] >= 1 && !$this->hasBadge($user_id, 'First Artist')) {
            $this->awardBadge($user_id, 'First Artist', 'Uploaded your first fanart', '🎨');
            $badges_awarded[] = 'First Artist';
        }
        
        if ($stats['fanart_count'] >= 10 && !$this->hasBadge($user_id, 'Art Enthusiast')) {
            $this->awardBadge($user_id, 'Art Enthusiast', 'Uploaded 10 fanarts', '🖼️');
            $badges_awarded[] = 'Art Enthusiast';
        }
        
        // Discussion badges
        if ($stats['discussion_count'] >= 1 && !$this->hasBadge($user_id, 'Conversation Starter')) {
            $this->awardBadge($user_id, 'Conversation Starter', 'Started your first discussion', '💬');
            $badges_awarded[] = 'Conversation Starter';
        }
        
        if ($stats['discussion_count'] >= 20 && !$this->hasBadge($user_id, 'Discussion Leader')) {
            $this->awardBadge($user_id, 'Discussion Leader', 'Started 20 discussions', '🗣️');
            $badges_awarded[] = 'Discussion Leader';
        }
        
        // Social badges
        if ($stats['follower_count'] >= 10 && !$this->hasBadge($user_id, 'Popular User')) {
            $this->awardBadge($user_id, 'Popular User', 'Gained 10 followers', '⭐');
            $badges_awarded[] = 'Popular User';
        }
        
        if ($stats['follower_count'] >= 50 && !$this->hasBadge($user_id, 'Community Star')) {
            $this->awardBadge($user_id, 'Community Star', 'Gained 50 followers', '🌟');
            $badges_awarded[] = 'Community Star';
        }
        
        // Experience badges
        $total_exp = $this->getTotalExperience($user_id);
        
        if ($total_exp >= 500 && !$this->hasBadge($user_id, 'Active Member')) {
            $this->awardBadge($user_id, 'Active Member', 'Earned 500 experience points', '🔥');
            $badges_awarded[] = 'Active Member';
        }
        
        if ($total_exp >= 2000 && !$this->hasBadge($user_id, 'Veteran Member')) {
            $this->awardBadge($user_id, 'Veteran Member', 'Earned 2000 experience points', '🎖️');
            $badges_awarded[] = 'Veteran Member';
        }
        
        // Level badges
        $user_level = $this->calculateLevel($total_exp);
        
        if ($user_level >= 5 && !$this->hasBadge($user_id, 'Level 5 Achieved')) {
            $this->awardBadge($user_id, 'Level 5 Achieved', 'Reached level 5', '🏅');
            $badges_awarded[] = 'Level 5 Achieved';
        }
        
        if ($user_level >= 10 && !$this->hasBadge($user_id, 'Level 10 Achieved')) {
            $this->awardBadge($user_id, 'Level 10 Achieved', 'Reached level 10', '👑');
            $badges_awarded[] = 'Level 10 Achieved';
        }
        
        return $badges_awarded;
    }

    // Get user statistics for badge calculation
    public function getUserStats($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT 
                (SELECT COUNT(*) FROM reviews WHERE user_id = ?) as review_count,
                (SELECT COUNT(*) FROM fanart WHERE user_id = ?) as fanart_count,
                (SELECT COUNT(*) FROM genre_discussions WHERE user_id = ?) as discussion_count,
                (SELECT COUNT(*) FROM debates WHERE user_id = ?) as debate_count,
                (SELECT COUNT(*) FROM follows WHERE followee_id = ?) as follower_count,
                (SELECT COUNT(*) FROM follows WHERE follower_id = ?) as following_count
        ");
        $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id, $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get experience breakdown for a user
    public function getExperienceBreakdown($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT action_type, SUM(points_earned) as total_points, COUNT(*) as action_count
            FROM user_experience 
            WHERE user_id = ? 
            GROUP BY action_type 
            ORDER BY total_points DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get recent experience gains
    public function getRecentExperience($user_id, $limit = 10) {
        // Ensure limit is an integer to prevent SQL injection
        $limit = (int) $limit;
        $stmt = $this->pdo->prepare("
            SELECT * FROM user_experience 
            WHERE user_id = ? 
            ORDER BY earned_at DESC 
            LIMIT $limit
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get leaderboard (top users by level/experience)
    public function getLeaderboard($type = 'level', $limit = 20) {
        // Ensure limit is an integer to prevent SQL injection
        $limit = (int) $limit;
        if ($type === 'level') {
            $stmt = $this->pdo->prepare("
                SELECT u.user_id, u.username, u.level, u.badge, u.profile_picture
                FROM users u 
                WHERE u.is_active = 1
                ORDER BY u.level DESC, u.created_at ASC
                LIMIT $limit
            ");
        } else {
            $stmt = $this->pdo->prepare("
                SELECT u.user_id, u.username, u.level, u.badge, u.profile_picture,
                       COALESCE(SUM(ue.points_earned), 0) as total_experience
                FROM users u 
                LEFT JOIN user_experience ue ON u.user_id = ue.user_id
                WHERE u.is_active = 1
                GROUP BY u.user_id
                ORDER BY total_experience DESC, u.created_at ASC
                LIMIT $limit
            ");
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Award experience for different actions
    public function awardExperienceForAction($user_id, $action) {
        $experience_map = [
            'review_submitted' => 20,
            'fanart_uploaded' => 15,
            'discussion_started' => 10,
            'debate_started' => 10,
            'comment_posted' => 5,
            'poll_created' => 8,
            'vote_cast' => 2,
            'profile_updated' => 5,
            'login_daily' => 1
        ];
        
        $points = $experience_map[$action] ?? 0;
        if ($points > 0) {
            return $this->addExperience($user_id, $action, $points, ucfirst(str_replace('_', ' ', $action)));
        }
        
        return false;
    }

    // Get experience needed for next level
    public function getExperienceForNextLevel($current_level) {
        // Formula: (level^2) * 100
        $next_level = $current_level + 1;
        return ($next_level * $next_level) * 100;
    }

    // Get progress to next level
    public function getLevelProgress($user_id) {
        $total_exp = $this->getTotalExperience($user_id);
        $current_level = $this->calculateLevel($total_exp);
        $current_level_exp = ($current_level * $current_level) * 100;
        $next_level_exp = $this->getExperienceForNextLevel($current_level);
        
        $progress_exp = $total_exp - $current_level_exp;
        $needed_exp = $next_level_exp - $current_level_exp;
        $progress_percentage = $needed_exp > 0 ? ($progress_exp / $needed_exp) * 100 : 100;
        
        return [
            'current_level' => $current_level,
            'total_experience' => $total_exp,
            'current_level_exp' => $current_level_exp,
            'next_level_exp' => $next_level_exp,
            'progress_exp' => $progress_exp,
            'needed_exp' => $needed_exp,
            'progress_percentage' => min(100, max(0, $progress_percentage))
        ];
    }
}
?>