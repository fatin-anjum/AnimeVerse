<?php
class FanArtModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function uploadFanArt($user_id, $title, $description, $file) {
        $targetDir = __DIR__ . '/../uploads/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $filename = 'fanart_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $filePath = $targetDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception("Failed to upload file.");
        }

        $stmt = $this->pdo->prepare("INSERT INTO fanart (user_id, title, description, filename) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $title, $description, $filename]);
    }

    public function deleteFanArt($fanart_id, $user_id) {
        $stmt = $this->pdo->prepare("SELECT filename FROM fanart WHERE fanart_id = ? AND user_id = ?");
        $stmt->execute([$fanart_id, $user_id]);
        $fanart = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fanart) throw new Exception("You do not have permission to delete this fan art.");

        $file_path = __DIR__ . '/../uploads/' . $fanart['filename'];
        if (file_exists($file_path)) unlink($file_path);

        $del = $this->pdo->prepare("DELETE FROM fanart WHERE fanart_id = ?");
        $del->execute([$fanart_id]);
    }

    public function addComment($fanart_id, $user_id, $comment) {
        $stmt = $this->pdo->prepare("INSERT INTO fanart_comments (fanart_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$fanart_id, $user_id, $comment]);
    }

    public function addHeart($fanart_id, $user_id) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO fanart_hearts (fanart_id, user_id) VALUES (?, ?)");
            $stmt->execute([$fanart_id, $user_id]);
        } catch (Exception $e) {
           
        }
    }

    public function getAllFanArt() {
        $stmt = $this->pdo->query("
            SELECT f.*, u.username,
            (SELECT COUNT(*) FROM fanart_hearts h WHERE h.fanart_id = f.fanart_id) as hearts
            FROM fanart f
            JOIN users u ON f.user_id = u.user_id
            ORDER BY f.created_at DESC
        ");
        $fanarts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($fanarts as &$art) {
            $stmt = $this->pdo->prepare("SELECT c.comment, u.username FROM fanart_comments c JOIN users u ON c.user_id = u.user_id WHERE c.fanart_id = ?");
            $stmt->execute([$art['fanart_id']]);
            $art['comments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $fanarts;
    }
}
