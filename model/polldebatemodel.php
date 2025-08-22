<?php
class PolldebateModel {
    private $pdo;
    private $maxOptions;

    public function __construct($pdo, $maxOptions = 5) {
        $this->pdo = $pdo;
        $this->maxOptions = $maxOptions;
    }



    public function getMaxOptions() {
        return $this->maxOptions;
    }

    public function createPoll($user_id, $title, $description, $options) {
      
        $options = array_slice(array_filter(array_map('trim', $options)), 0, $this->maxOptions);

        if (empty($title) || count($options) < 2) {
            throw new Exception("Poll must have a title and at least 2 options (max {$this->maxOptions}).");
        }

        $stmt = $this->pdo->prepare("INSERT INTO polls (created_by, title, description) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $title, $description]);
        $poll_id = $this->pdo->lastInsertId();

        $stmtOpt = $this->pdo->prepare("INSERT INTO poll_options (poll_id, option_text) VALUES (?, ?)");
        foreach ($options as $opt) {
            $stmtOpt->execute([$poll_id, $opt]);
        }
    }

    public function getAllPolls() {
        $stmt = $this->pdo->query("SELECT p.*, u.username FROM polls p JOIN users u ON p.created_by = u.user_id ORDER BY p.created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPollOptions($poll_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM poll_options WHERE poll_id=?");
        $stmt->execute([$poll_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function votePoll($user_id, $option_id) {
        
        $stmtCheck = $this->pdo->prepare("
            DELETE pl FROM poll_votes pl
            JOIN poll_options po ON pl.option_id = po.option_id
            WHERE pl.user_id=? AND po.poll_id = (SELECT poll_id FROM poll_options WHERE option_id=?)
        ");
        $stmtCheck->execute([$user_id, $option_id]);

        $stmt = $this->pdo->prepare("INSERT INTO poll_votes (user_id, option_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $option_id]);
    }

    public function getPollVotes($option_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS votes FROM poll_votes WHERE option_id=?");
        $stmt->execute([$option_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['votes'] ?? 0;
    }

 

    public function createDebate($user_id, $title, $content) {
        $stmt = $this->pdo->prepare("INSERT INTO debates (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $title, $content]);
    }

    public function debateExists($user_id, $title) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM debates WHERE user_id=? AND title=?");
        $stmt->execute([$user_id, $title]);
        return $stmt->fetchColumn() > 0;
    }

    public function getAllDebates() {
        $stmt = $this->pdo->query("SELECT d.*, u.username FROM debates d JOIN users u ON d.user_id = u.user_id ORDER BY d.created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addDebateReply($debate_id, $user_id, $content) {
        $stmt = $this->pdo->prepare("INSERT INTO debate_replies (debate_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$debate_id, $user_id, $content]);
    }

    public function voteDebateReply($reply_id, $user_id, $upvote = true) {
        $vote = $upvote ? 1 : -1;

       
        $stmtCheck = $this->pdo->prepare("SELECT vote FROM debate_reply_votes WHERE reply_id=? AND user_id=?");
        $stmtCheck->execute([$reply_id, $user_id]);
        $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
        
            if ($existing['vote'] == $vote) return;

           
            $stmtUpdate = $this->pdo->prepare("UPDATE debate_reply_votes SET vote=? WHERE reply_id=? AND user_id=?");
            $stmtUpdate->execute([$vote, $reply_id, $user_id]);

           
            $stmtAdjust = $this->pdo->prepare("UPDATE debate_replies SET votes = votes + ? WHERE reply_id=?");
            $stmtAdjust->execute([$vote, $reply_id]); // +2 or -2 because we reverse previous vote first
        } else {
        
            $stmtInsert = $this->pdo->prepare("INSERT INTO debate_reply_votes (reply_id, user_id, vote) VALUES (?, ?, ?)");
            $stmtInsert->execute([$reply_id, $user_id, $vote]);

           
            $stmtUpdate = $this->pdo->prepare("UPDATE debate_replies SET votes = votes + ? WHERE reply_id=?");
            $stmtUpdate->execute([$vote, $reply_id]);
        }
    }


    public function getDebateReplies($debate_id) {
        $stmt = $this->pdo->prepare("SELECT r.*, u.username FROM debate_replies r JOIN users u ON r.user_id = u.user_id WHERE r.debate_id=? ORDER BY r.created_at ASC");
        $stmt->execute([$debate_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
