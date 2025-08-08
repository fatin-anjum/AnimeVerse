<?php

$page = $_GET['page'] ?? 'home';

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
    default:
        echo "Page not found.";
        break;
}
