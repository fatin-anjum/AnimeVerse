<?php

require_once __DIR__ . "/db.php";

$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        require __DIR__ . "/controller/logincontroller.php";
        break;
    case 'profile':
        require __DIR__ . "/controller/profilecontroller.php";
        break;
    case 'home':
        require __DIR__ . "/controller/homecontroller.php";
        break;
    case 'goodbye':
        require __DIR__ . "/controller/goodbyecontroller.php";
        break;
    case 'discussion':
        require __DIR__ . "/controller/discussioncontroller.php";
        break;
    case 'polldebate':
        require __DIR__ . "/controller/polldebatecontroller.php";
        break;
    case 'fanart':
        require __DIR__ . "/controller/fanartcontroller.php";
        break;
    default:
        echo "Page not found.";
        break;
}
