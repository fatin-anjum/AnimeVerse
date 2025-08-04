<?php

$host = 'localhost';         // your DB host
$dbname = 'animeverse';      // database name
$user = 'root';              // your DB username
$pass = '';                  // your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
