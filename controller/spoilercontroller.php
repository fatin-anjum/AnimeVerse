<?php
require_once __DIR__ . '/../model/SpoilerModel.php';

class SpoilerController {
    private $spoilerModel;

    public function __construct($pdo) {
        $this->spoilerModel = new SpoilerModel($pdo);
    }

    // Display spoiler management interface
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        $spoiler_content = $this->spoilerModel->getAllSpoilerContent();
        $spoiler_stats = $this->spoilerModel->getSpoilerStats();
        $spoilerModel = $this->spoilerModel; // Make model available to view
        
        require __DIR__ . '/../view/spoilerview.php';
    }

    // Handle marking content as spoiler
    public function markAsSpoiler() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content_type = $_POST['content_type'] ?? null;
            $content_id = $_POST['content_id'] ?? null;
            $spoiler_warning = trim($_POST['spoiler_warning'] ?? '');
            $anime_id = $_POST['anime_id'] ?? null;

            // Validation
            if (!$content_type || !$content_id || empty($spoiler_warning)) {
                $_SESSION['error'] = "All fields are required to mark content as spoiler.";
                header("Location: " . ($_POST['redirect_url'] ?? "?page=spoiler"));
                exit;
            }

            $success = false;
            switch ($content_type) {
                case 'fanart':
                    $success = $this->spoilerModel->markFanartAsSpoiler($content_id, $spoiler_warning);
                    break;
                case 'discussion':
                    $success = $this->spoilerModel->markDiscussionAsSpoiler($content_id, $spoiler_warning);
                    break;
                case 'debate':
                    $success = $this->spoilerModel->markDebateAsSpoiler($content_id, $spoiler_warning);
                    break;
                default:
                    $_SESSION['error'] = "Invalid content type.";
                    header("Location: " . ($_POST['redirect_url'] ?? "?page=spoiler"));
                    exit;
            }

            // Also add to spoiler_tags table for tracking
            if ($success && $anime_id) {
                $this->spoilerModel->addSpoilerTag($content_type, $content_id, $spoiler_warning, $anime_id);
            }

            $_SESSION['message'] = $success ? "Content marked as spoiler successfully!" : "Failed to mark content as spoiler.";
            header("Location: " . ($_POST['redirect_url'] ?? "?page=spoiler"));
            exit;
        }
    }

    // Handle unmarking content as spoiler
    public function unmarkAsSpoiler() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        $content_type = $_GET['content_type'] ?? null;
        $content_id = $_GET['content_id'] ?? null;
        $redirect_url = $_GET['redirect_url'] ?? "?page=spoiler";

        if (!$content_type || !$content_id) {
            $_SESSION['error'] = "Invalid parameters.";
            header("Location: $redirect_url");
            exit;
        }

        $success = false;
        switch ($content_type) {
            case 'fanart':
                $success = $this->spoilerModel->unmarkFanartAsSpoiler($content_id);
                break;
            case 'discussion':
                $success = $this->spoilerModel->unmarkDiscussionAsSpoiler($content_id);
                break;
            case 'debate':
                $success = $this->spoilerModel->unmarkDebateAsSpoiler($content_id);
                break;
        }

        // Remove from spoiler_tags table
        if ($success) {
            $this->spoilerModel->removeSpoilerTag($content_type, $content_id);
        }

        $_SESSION['message'] = $success ? "Spoiler tag removed successfully!" : "Failed to remove spoiler tag.";
        header("Location: $redirect_url");
        exit;
    }

    // Display spoiler content for a specific anime
    public function viewByAnime($anime_id) {
        if (!$anime_id) {
            header("Location: ?page=spoiler");
            exit;
        }

        $spoiler_content = $this->spoilerModel->getSpoilerContentByAnime($anime_id);
        
        require __DIR__ . '/../view/spoilerbyanimelview.php';
    }

    // Handle AJAX request to toggle spoiler visibility
    public function toggleVisibility() {
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
        $content_type = $input['content_type'] ?? null;
        $content_id = $input['content_id'] ?? null;

        if (!$content_type || !$content_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid parameters']);
            exit;
        }

        // Check if user can view spoiler
        $can_view = $this->spoilerModel->canUserViewSpoiler($_SESSION['user_id'], $content_type, $content_id);
        
        if (!$can_view) {
            http_response_code(403);
            echo json_encode(['error' => 'Permission denied']);
            exit;
        }

        // Store in session that user has chosen to view this spoiler
        if (!isset($_SESSION['viewed_spoilers'])) {
            $_SESSION['viewed_spoilers'] = [];
        }
        $_SESSION['viewed_spoilers'][$content_type . '_' . $content_id] = true;

        echo json_encode(['success' => true]);
        exit;
    }

    // Update spoiler warning
    public function updateWarning() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content_type = $_POST['content_type'] ?? null;
            $content_id = $_POST['content_id'] ?? null;
            $spoiler_warning = trim($_POST['spoiler_warning'] ?? '');

            if (!$content_type || !$content_id || empty($spoiler_warning)) {
                $_SESSION['error'] = "All fields are required.";
                header("Location: ?page=spoiler");
                exit;
            }

            $success = $this->spoilerModel->updateSpoilerWarning($content_type, $content_id, $spoiler_warning);
            
            // Also update the main content table
            switch ($content_type) {
                case 'fanart':
                    $this->spoilerModel->markFanartAsSpoiler($content_id, $spoiler_warning);
                    break;
                case 'discussion':
                    $this->spoilerModel->markDiscussionAsSpoiler($content_id, $spoiler_warning);
                    break;
                case 'debate':
                    $this->spoilerModel->markDebateAsSpoiler($content_id, $spoiler_warning);
                    break;
            }

            $_SESSION['message'] = $success ? "Spoiler warning updated successfully!" : "Failed to update spoiler warning.";
            header("Location: ?page=spoiler");
            exit;
        }
    }

    // Handle different actions
    public function handle() {
        $action = $_GET['action'] ?? 'index';
        $id = $_GET['id'] ?? null;

        switch ($action) {
            case 'index':
                $this->index();
                break;
            case 'mark':
                $this->markAsSpoiler();
                break;
            case 'unmark':
                $this->unmarkAsSpoiler();
                break;
            case 'view_anime':
                $this->viewByAnime($id);
                break;
            case 'toggle':
                $this->toggleVisibility();
                break;
            case 'update':
                $this->updateWarning();
                break;
            default:
                $this->index();
                break;
        }
    }
}
?>