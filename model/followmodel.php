<?php
class FollowModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get PDO connection for use in controllers
    public function getPDO() {
        return $this->pdo;
    }

    // Follow a user
    public function followUser($follower_id, $followee_id) {
        // Check if already following
        if ($this->isFollowing($follower_id, $followee_id)) {
            return false; // Already following
        }

        // Prevent self-following
        if ($follower_id == $followee_id) {
            return false;
        }

        $stmt = $this->pdo->prepare("INSERT INTO follows (follower_id, followee_id) VALUES (?, ?)");
        return $stmt->execute([$follower_id, $followee_id]);
    }

    // Unfollow a user
    public function unfollowUser($follower_id, $followee_id) {
        $stmt = $this->pdo->prepare("DELETE FROM follows WHERE follower_id = ? AND followee_id = ?");
        return $stmt->execute([$follower_id, $followee_id]);
    }

    // Check if user A is following user B
    public function isFollowing($follower_id, $followee_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM follows WHERE follower_id = ? AND followee_id = ?");
        $stmt->execute([$follower_id, $followee_id]);
        return $stmt->fetchColumn() > 0;
    }

    // Get followers of a user
    public function getFollowers($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT u.user_id, u.username, u.profile_picture, u.level, u.badge, u.bio, f.followed_at
            FROM follows f 
            JOIN users u ON f.follower_id = u.user_id 
            WHERE f.followee_id = ? 
            ORDER BY f.followed_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get users that someone is following
    public function getFollowing($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT u.user_id, u.username, u.profile_picture, u.level, u.badge, u.bio, f.followed_at
            FROM follows f 
            JOIN users u ON f.followee_id = u.user_id 
            WHERE f.follower_id = ? 
            ORDER BY f.followed_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get follower count
    public function getFollowerCount($user_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM follows WHERE followee_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }

    // Get following count
    public function getFollowingCount($user_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM follows WHERE follower_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }

    // Get mutual follows (users who follow each other)
    public function getMutualFollows($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT u.user_id, u.username, u.profile_picture, u.level, u.badge
            FROM users u
            WHERE u.user_id IN (
                SELECT f1.followee_id 
                FROM follows f1 
                WHERE f1.follower_id = ? 
                AND f1.followee_id IN (
                    SELECT f2.follower_id 
                    FROM follows f2 
                    WHERE f2.followee_id = ?
                )
            )
            ORDER BY u.username ASC
        ");
        $stmt->execute([$user_id, $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Suggest users to follow (users with similar interests)
    public function getSuggestedUsers($user_id, $limit = 10) {
        // Ensure limit is an integer to prevent SQL injection
        $limit = (int) $limit;
        $stmt = $this->pdo->prepare("
            SELECT DISTINCT u.user_id, u.username, u.profile_picture, u.level, u.badge,
                   COUNT(DISTINCT fa.fanart_id) as fanart_count,
                   COUNT(DISTINCT r.review_id) as review_count,
                   COUNT(DISTINCT gd.discussion_id) as discussion_count,
                   (COUNT(DISTINCT fa.fanart_id) + COUNT(DISTINCT r.review_id) + COUNT(DISTINCT gd.discussion_id)) as total_activity
            FROM users u
            LEFT JOIN fanart fa ON u.user_id = fa.user_id
            LEFT JOIN reviews r ON u.user_id = r.user_id  
            LEFT JOIN genre_discussions gd ON u.user_id = gd.user_id
            WHERE u.user_id != ?
            AND u.user_id NOT IN (
                SELECT followee_id FROM follows WHERE follower_id = ?
            )
            AND u.is_active = 1
            GROUP BY u.user_id, u.username, u.profile_picture, u.level, u.badge
            HAVING total_activity > 0
            ORDER BY total_activity DESC, u.created_at DESC
            LIMIT $limit
        ");
        $stmt->execute([$user_id, $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get recent activity from followed users
    public function getFollowingActivity($user_id, $limit = 20) {
        // Ensure limit is an integer to prevent SQL injection
        $limit = (int) $limit;
        $stmt = $this->pdo->prepare("
            (SELECT 'fanart' as type, fa.fanart_id as content_id, fa.title, fa.created_at,
                    u.user_id, u.username, u.profile_picture, NULL as anime_title
             FROM fanart fa 
             JOIN users u ON fa.user_id = u.user_id
             WHERE fa.user_id IN (SELECT followee_id FROM follows WHERE follower_id = ?)
            )
            UNION ALL
            (SELECT 'review' as type, r.review_id as content_id, 
                    CONCAT('Review for ', a.title) as title, r.reviewed_at as created_at,
                    u.user_id, u.username, u.profile_picture, a.title as anime_title
             FROM reviews r 
             JOIN users u ON r.user_id = u.user_id
             JOIN anime a ON r.anime_id = a.anime_id
             WHERE r.user_id IN (SELECT followee_id FROM follows WHERE follower_id = ?)
            )
            UNION ALL
            (SELECT 'discussion' as type, gd.discussion_id as content_id, gd.title, gd.posted_at as created_at,
                    u.user_id, u.username, u.profile_picture, NULL as anime_title
             FROM genre_discussions gd 
             JOIN users u ON gd.user_id = u.user_id
             WHERE gd.user_id IN (SELECT followee_id FROM follows WHERE follower_id = ?)
            )
            UNION ALL
            (SELECT 'debate' as type, d.debate_id as content_id, d.title, d.created_at,
                    u.user_id, u.username, u.profile_picture, NULL as anime_title
             FROM debates d 
             JOIN users u ON d.user_id = u.user_id
             WHERE d.user_id IN (SELECT followee_id FROM follows WHERE follower_id = ?)
            )
            ORDER BY created_at DESC 
            LIMIT $limit
        ");
        $stmt->execute([$user_id, $user_id, $user_id, $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search users by username
    public function searchUsers($query, $current_user_id = null, $limit = 20) {
        // Ensure limit is an integer to prevent SQL injection
        $limit = (int) $limit;
        $search_term = "%{$query}%";
        
        $sql = "
            SELECT u.user_id, u.username, u.profile_picture, u.level, u.badge, u.bio,
                   COUNT(DISTINCT f.follower_id) as follower_count";
        
        if ($current_user_id) {
            $sql .= ", (SELECT COUNT(*) FROM follows WHERE follower_id = ? AND followee_id = u.user_id) as is_following";
        }
        
        $sql .= "
            FROM users u
            LEFT JOIN follows f ON u.user_id = f.followee_id
            WHERE u.username LIKE ? AND u.is_active = 1";
        
        if ($current_user_id) {
            $sql .= " AND u.user_id != ?";
        }
        
        $sql .= "
            GROUP BY u.user_id
            ORDER BY follower_count DESC, u.username ASC
            LIMIT $limit";
        
        $params = $current_user_id ? [$current_user_id, $search_term, $current_user_id] : [$search_term];
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get popular users (most followed)
    public function getPopularUsers($limit = 10) {
        // Ensure limit is an integer to prevent SQL injection
        $limit = (int) $limit;
        $stmt = $this->pdo->prepare("
            SELECT u.user_id, u.username, u.profile_picture, u.level, u.badge, u.bio,
                   COUNT(f.follower_id) as follower_count
            FROM users u
            LEFT JOIN follows f ON u.user_id = f.followee_id
            WHERE u.is_active = 1
            GROUP BY u.user_id
            HAVING follower_count > 0
            ORDER BY follower_count DESC, u.created_at ASC
            LIMIT $limit
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get follow stats for a user
    public function getFollowStats($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT 
                (SELECT COUNT(*) FROM follows WHERE followee_id = ?) as followers,
                (SELECT COUNT(*) FROM follows WHERE follower_id = ?) as following,
                (SELECT COUNT(*) FROM follows f1 
                 WHERE f1.follower_id = ? 
                 AND f1.followee_id IN (
                     SELECT f2.follower_id FROM follows f2 WHERE f2.followee_id = ?
                 )) as mutual_follows
        ");
        $stmt->execute([$user_id, $user_id, $user_id, $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Remove all follows for a user (when account is deleted)
    public function removeAllUserFollows($user_id) {
        $stmt1 = $this->pdo->prepare("DELETE FROM follows WHERE follower_id = ?");
        $stmt2 = $this->pdo->prepare("DELETE FROM follows WHERE followee_id = ?");
        
        $success1 = $stmt1->execute([$user_id]);
        $success2 = $stmt2->execute([$user_id]);
        
        return $success1 && $success2;
    }
}
?>