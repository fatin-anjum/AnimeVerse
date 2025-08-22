<?php
class DiscussionModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    
    public function getThreadsByGenre($genre_id) {
        $stmt = $this->pdo->prepare("
            SELECT gd.*, u.username,
                   (SELECT COUNT(*) FROM thread_likes tl WHERE tl.discussion_id = gd.discussion_id AND is_like = 1) AS likes,
                   (SELECT COUNT(*) FROM thread_likes tl WHERE tl.discussion_id = gd.discussion_id AND is_like = 0) AS dislikes
            FROM genre_discussions gd
            JOIN users u ON gd.user_id = u.user_id
            WHERE gd.genre_id = ?
            ORDER BY gd.posted_at DESC
        ");
        $stmt->execute([$genre_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function createThread($genre_id, $user_id, $title, $content) {
        $stmt = $this->pdo->prepare("
            INSERT INTO genre_discussions (genre_id, user_id, title, content)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$genre_id, $user_id, $title, $content]);
    }

    
    public function addLikeDislike($discussion_id, $user_id, $is_like) {
        
        $stmt = $this->pdo->prepare("
            DELETE FROM thread_likes WHERE discussion_id = ? AND user_id = ?
        ");
        $stmt->execute([$discussion_id, $user_id]);

        
        $stmt = $this->pdo->prepare("
            INSERT INTO thread_likes (discussion_id, user_id, is_like)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$discussion_id, $user_id, $is_like]);
    }

   
    public function addReply($discussion_id, $user_id, $content) {
        $stmt = $this->pdo->prepare("
            INSERT INTO thread_replies (discussion_id, user_id, content)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$discussion_id, $user_id, $content]);
    }

    
    public function getReplies($discussion_id) {
        $stmt = $this->pdo->prepare("
            SELECT tr.*, u.username
            FROM thread_replies tr
            JOIN users u ON tr.user_id = u.user_id
            WHERE tr.discussion_id = ?
            ORDER BY tr.replied_at ASC
        ");
        $stmt->execute([$discussion_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
