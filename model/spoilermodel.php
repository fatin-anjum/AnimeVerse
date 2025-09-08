<?php
class SpoilerModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Add spoiler tag to content
    public function addSpoilerTag($content_type, $content_id, $spoiler_warning, $anime_id = null) {
        $stmt = $this->pdo->prepare("
            INSERT INTO spoiler_tags (content_type, content_id, spoiler_warning, anime_id) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$content_type, $content_id, $spoiler_warning, $anime_id]);
    }

    // Remove spoiler tag from content
    public function removeSpoilerTag($content_type, $content_id) {
        $stmt = $this->pdo->prepare("
            DELETE FROM spoiler_tags 
            WHERE content_type = ? AND content_id = ?
        ");
        return $stmt->execute([$content_type, $content_id]);
    }

    // Get spoiler tag for specific content
    public function getSpoilerTag($content_type, $content_id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM spoiler_tags 
            WHERE content_type = ? AND content_id = ?
        ");
        $stmt->execute([$content_type, $content_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update spoiler warning for existing content
    public function updateSpoilerWarning($content_type, $content_id, $spoiler_warning) {
        $stmt = $this->pdo->prepare("
            UPDATE spoiler_tags 
            SET spoiler_warning = ? 
            WHERE content_type = ? AND content_id = ?
        ");
        return $stmt->execute([$spoiler_warning, $content_type, $content_id]);
    }

    // Get all spoiler content for a specific anime
    public function getSpoilerContentByAnime($anime_id) {
        $stmt = $this->pdo->prepare("
            SELECT st.*, 
                   CASE 
                       WHEN st.content_type = 'review' THEN (SELECT CONCAT(u.username, ' - Review') FROM reviews r JOIN users u ON r.user_id = u.user_id WHERE r.review_id = st.content_id)
                       WHEN st.content_type = 'fanart' THEN (SELECT CONCAT(u.username, ' - ', f.title) FROM fanart f JOIN users u ON f.user_id = u.user_id WHERE f.fanart_id = st.content_id)
                       WHEN st.content_type = 'discussion' THEN (SELECT gd.title FROM genre_discussions gd WHERE gd.discussion_id = st.content_id)
                       WHEN st.content_type = 'debate' THEN (SELECT d.title FROM debates d WHERE d.debate_id = st.content_id)
                   END as content_title
            FROM spoiler_tags st 
            WHERE st.anime_id = ?
            ORDER BY st.created_at DESC
        ");
        $stmt->execute([$anime_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mark fanart as spoiler
    public function markFanartAsSpoiler($fanart_id, $spoiler_warning) {
        $stmt = $this->pdo->prepare("
            UPDATE fanart 
            SET is_spoiler = 1, spoiler_warning = ? 
            WHERE fanart_id = ?
        ");
        return $stmt->execute([$spoiler_warning, $fanart_id]);
    }

    // Unmark fanart as spoiler
    public function unmarkFanartAsSpoiler($fanart_id) {
        $stmt = $this->pdo->prepare("
            UPDATE fanart 
            SET is_spoiler = 0, spoiler_warning = NULL 
            WHERE fanart_id = ?
        ");
        return $stmt->execute([$fanart_id]);
    }

    // Mark discussion as spoiler
    public function markDiscussionAsSpoiler($discussion_id, $spoiler_warning) {
        $stmt = $this->pdo->prepare("
            UPDATE genre_discussions 
            SET is_spoiler = 1, spoiler_warning = ? 
            WHERE discussion_id = ?
        ");
        return $stmt->execute([$spoiler_warning, $discussion_id]);
    }

    // Unmark discussion as spoiler
    public function unmarkDiscussionAsSpoiler($discussion_id) {
        $stmt = $this->pdo->prepare("
            UPDATE genre_discussions 
            SET is_spoiler = 0, spoiler_warning = NULL 
            WHERE discussion_id = ?
        ");
        return $stmt->execute([$discussion_id]);
    }

    // Mark debate as spoiler
    public function markDebateAsSpoiler($debate_id, $spoiler_warning) {
        $stmt = $this->pdo->prepare("
            UPDATE debates 
            SET is_spoiler = 1, spoiler_warning = ? 
            WHERE debate_id = ?
        ");
        return $stmt->execute([$spoiler_warning, $debate_id]);
    }

    // Unmark debate as spoiler
    public function unmarkDebateAsSpoiler($debate_id) {
        $stmt = $this->pdo->prepare("
            UPDATE debates 
            SET is_spoiler = 0, spoiler_warning = NULL 
            WHERE debate_id = ?
        ");
        return $stmt->execute([$debate_id]);
    }

    // Get all content with spoilers for moderation
    public function getAllSpoilerContent($limit = 50) {
        // Ensure limit is an integer to prevent SQL injection
        $limit = (int) $limit;
        $stmt = $this->pdo->prepare("
            (SELECT 'review' as type, r.review_id as id, r.is_spoiler, r.spoiler_warning, 
                    u.username, a.title as anime_title, r.reviewed_at as created_at
             FROM reviews r 
             JOIN users u ON r.user_id = u.user_id 
             JOIN anime a ON r.anime_id = a.anime_id 
             WHERE r.is_spoiler = 1)
            UNION ALL
            (SELECT 'fanart' as type, f.fanart_id as id, f.is_spoiler, f.spoiler_warning, 
                    u.username, f.title as anime_title, f.created_at
             FROM fanart f 
             JOIN users u ON f.user_id = u.user_id 
             WHERE f.is_spoiler = 1)
            UNION ALL
            (SELECT 'discussion' as type, gd.discussion_id as id, gd.is_spoiler, gd.spoiler_warning, 
                    u.username, gd.title as anime_title, gd.posted_at as created_at
             FROM genre_discussions gd 
             JOIN users u ON gd.user_id = u.user_id 
             WHERE gd.is_spoiler = 1)
            UNION ALL
            (SELECT 'debate' as type, d.debate_id as id, d.is_spoiler, d.spoiler_warning, 
                    u.username, d.title as anime_title, d.created_at
             FROM debates d 
             JOIN users u ON d.user_id = u.user_id 
             WHERE d.is_spoiler = 1)
            ORDER BY created_at DESC 
            LIMIT $limit
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Check if user can view spoiler content (based on their preferences or permissions)
    public function canUserViewSpoiler($user_id, $content_type, $content_id) {
        // For now, all logged-in users can view spoilers after warning
        // This could be extended to include user preferences
        return isset($user_id);
    }

    // Get spoiler statistics
    public function getSpoilerStats() {
        $stmt = $this->pdo->prepare("
            SELECT 
                (SELECT COUNT(*) FROM reviews WHERE is_spoiler = 1) as spoiler_reviews,
                (SELECT COUNT(*) FROM fanart WHERE is_spoiler = 1) as spoiler_fanart,
                (SELECT COUNT(*) FROM genre_discussions WHERE is_spoiler = 1) as spoiler_discussions,
                (SELECT COUNT(*) FROM debates WHERE is_spoiler = 1) as spoiler_debates
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mark review as spoiler
    public function markReviewAsSpoiler($review_id, $spoiler_warning) {
        $stmt = $this->pdo->prepare("
            UPDATE reviews 
            SET is_spoiler = 1, spoiler_warning = ? 
            WHERE review_id = ?
        ");
        return $stmt->execute([$spoiler_warning, $review_id]);
    }

    // Unmark review as spoiler
    public function unmarkReviewAsSpoiler($review_id) {
        $stmt = $this->pdo->prepare("
            UPDATE reviews 
            SET is_spoiler = 0, spoiler_warning = NULL 
            WHERE review_id = ?
        ");
        return $stmt->execute([$review_id]);
    }

    // Get content by ID and type
    public function getContentById($content_type, $content_id) {
        switch ($content_type) {
            case 'fanart':
                $stmt = $this->pdo->prepare("
                    SELECT f.*, u.username 
                    FROM fanart f 
                    JOIN users u ON f.user_id = u.user_id 
                    WHERE f.fanart_id = ?
                ");
                break;
            case 'discussion':
                $stmt = $this->pdo->prepare("
                    SELECT gd.*, u.username, g.name as genre_name 
                    FROM genre_discussions gd 
                    JOIN users u ON gd.user_id = u.user_id 
                    LEFT JOIN genres g ON gd.genre_id = g.genre_id 
                    WHERE gd.discussion_id = ?
                ");
                break;
            case 'debate':
                $stmt = $this->pdo->prepare("
                    SELECT d.*, u.username 
                    FROM debates d 
                    JOIN users u ON d.user_id = u.user_id 
                    WHERE d.debate_id = ?
                ");
                break;
            case 'review':
                $stmt = $this->pdo->prepare("
                    SELECT r.*, u.username, a.title as anime_title 
                    FROM reviews r 
                    JOIN users u ON r.user_id = u.user_id 
                    JOIN anime a ON r.anime_id = a.anime_id 
                    WHERE r.review_id = ?
                ");
                break;
            default:
                return null;
        }
        
        $stmt->execute([$content_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all content with their IDs for management
    public function getAllContentWithIds() {
        $result = [
            'fanart' => [],
            'discussions' => [],
            'debates' => [],
            'reviews' => []
        ];
        
        // Get fanart
        $stmt = $this->pdo->query("
            SELECT f.fanart_id as id, f.title, f.is_spoiler, f.spoiler_warning, 
                   u.username, f.created_at
            FROM fanart f 
            JOIN users u ON f.user_id = u.user_id 
            ORDER BY f.created_at DESC
        ");
        $result['fanart'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get discussions
        $stmt = $this->pdo->query("
            SELECT gd.discussion_id as id, gd.title, gd.is_spoiler, gd.spoiler_warning, 
                   u.username, g.name as genre_name, gd.posted_at as created_at
            FROM genre_discussions gd 
            JOIN users u ON gd.user_id = u.user_id 
            LEFT JOIN genres g ON gd.genre_id = g.genre_id 
            ORDER BY gd.posted_at DESC
        ");
        $result['discussions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get debates
        $stmt = $this->pdo->query("
            SELECT d.debate_id as id, d.title, d.is_spoiler, d.spoiler_warning, 
                   u.username, d.created_at
            FROM debates d 
            JOIN users u ON d.user_id = u.user_id 
            ORDER BY d.created_at DESC
        ");
        $result['debates'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get reviews
        $stmt = $this->pdo->query("
            SELECT r.review_id as id, CONCAT('Review: ', a.title) as title, 
                   r.is_spoiler, r.spoiler_warning, u.username, a.title as anime_title, 
                   r.reviewed_at as created_at
            FROM reviews r 
            JOIN users u ON r.user_id = u.user_id 
            JOIN anime a ON r.anime_id = a.anime_id 
            ORDER BY r.reviewed_at DESC
        ");
        $result['reviews'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }
}
?>