<?php
session_start();
require_once __DIR__ . '/../model/HomeModel.php';

$model = new HomeModel();

$userEmail = $_SESSION['user_email'] ?? null;
$inventoryCount = $model->getInventoryCount();

require_once __DIR__ . '/../view/homeview.php';
