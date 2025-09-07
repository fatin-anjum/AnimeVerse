<?php
session_start();
require_once __DIR__ . "/db.php";

// Determine which page to load
// If no page is specified and user is not logged in, redirect to login
if (!isset($_GET['page']) && !isset($_SESSION['user_id'])) {
    $page = 'login';
} else {
    $page = $_GET['page'] ?? 'home';
}

switch ($page) {

    case 'home':
        require __DIR__ . "/controller/HomeController.php";
        $controller = new HomeController($pdo);
        $controller->index();
        break;

    case 'login':
        require __DIR__ . "/controller/LoginController.php";
        $controller = new LoginController($pdo);
        $controller->index();
        break;

    case 'register':
        require __DIR__ . "/controller/registercontroller.php";
        $controller = new RegisterController($pdo);
        $controller->index();
        break;

    case 'logout':
        require __DIR__ . "/controller/logoutcontroller.php";
        $controller = new LogoutController($pdo);
        $controller->index();
        break;

    case 'profile': // Standard profile page
        require __DIR__ . "/controller/ProfileController.php";
        $controller = new ProfileController($pdo);
        $controller->index();
        break;

    case 'myprofile': // Instagram-like profile
        require __DIR__ . "/controller/MyProfileController.php";
        $controller = new MyProfileController($pdo);
        $controller->index();
        break;

    case 'collectibles':
        require __DIR__ . "/controller/CollectiblesController.php";
        $controller = new CollectiblesController($pdo);
        $controller->handle(); // this will internally include collectiblesview.php
        break;

    case 'fanart':
        require __DIR__ . "/controller/FanartController.php";
        $controller = new FanartController($pdo);
        $controller->index();
        break;

    case 'discussion':
        require __DIR__ . "/controller/DiscussionController.php";
        $controller = new DiscussionController($pdo);
        $action = $_GET['action'] ?? 'index';
        $id = $_GET['id'] ?? null;
        if ($action === 'index') {
            $controller->index();
        } elseif ($action === 'view' && $id) {
            $controller->view($id);
        } else {
            echo "Invalid discussion action.";
        }
        break;

    case 'polldebate':
        require __DIR__ . "/controller/polldebatecontroller.php";
        $controller = new PolldebateController($pdo);
        $controller->handle();
        break;

    case 'debate':
        require __DIR__ . "/controller/DebateController.php";
        $controller = new DebateController($pdo);
        $controller->index();
        break;

    case 'review':
        require __DIR__ . "/controller/ReviewController.php";
        $controller = new ReviewController($pdo);
        $action = $_GET['action'] ?? 'display';
        $id = $_GET['id'] ?? null;

        if ($action === 'submit') {
            $controller->submit();
        } elseif ($action === 'display' && $id) {
            $controller->display($id);
        } else {
            echo "Invalid review action.";
        }
        break;

    case 'animereview':
        require __DIR__ . "/controller/AnimeReviewController.php";
        $controller = new AnimeReviewController($pdo);
        $controller->handle();
        break;

    case 'spoiler':
        require __DIR__ . "/controller/SpoilerController.php";
        $controller = new SpoilerController($pdo);
        $controller->handle();
        break;

    case 'follow':
        require __DIR__ . "/controller/FollowController.php";
        $controller = new FollowController($pdo);
        $controller->handle();
        break;

    case 'badge':
        require __DIR__ . "/controller/BadgeController.php";
        $controller = new BadgeController($pdo);
        $controller->handle();
        break;

    case 'viewinvoice':
        require __DIR__ . "/view/viewinvoice.php";
        break;

    case 'goodbye':
        require __DIR__ . "/controller/goodbyecontroller.php";
        $controller = new GoodbyeController();
        $controller->index();
        break;

    default:
        echo "Page not found.";
        break;
}
?>
