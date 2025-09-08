<?php
/**
 * Update Anime Images with High Quality URLs
 * This script updates anime cover images with higher resolution URLs to prevent pixelation
 */

// Database configuration
$host = 'localhost';
$dbname = 'animeverse';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully!\n";
    
    // High quality image URLs with better resolution
    $updates = [
        "UPDATE `anime` SET `cover_image` = 'https://cdn.myanimelist.net/images/anime/10/47347l.jpg' WHERE `title` = 'Attack on Titan'",
        "UPDATE `anime` SET `cover_image` = 'https://cdn.myanimelist.net/images/anime/1286/99889l.jpg' WHERE `title` = 'Demon Slayer'", 
        "UPDATE `anime` SET `cover_image` = 'https://cdn.myanimelist.net/images/anime/6/73245l.jpg' WHERE `title` = 'One Piece'",
        "UPDATE `anime` SET `cover_image` = 'https://cdn.myanimelist.net/images/anime/10/78745l.jpg' WHERE `title` = 'My Hero Academia'",
        "UPDATE `anime` SET `cover_image` = 'https://cdn.myanimelist.net/images/anime/9/9453l.jpg' WHERE `title` = 'Death Note'"
    ];
    
    foreach ($updates as $sql) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        echo "✓ Updated anime cover image with higher quality URL\n";
    }
    
    // Verify updates
    echo "\nVerifying updates:\n";
    $stmt = $pdo->prepare("SELECT anime_id, title, cover_image FROM anime WHERE anime_id IN (1,2,3,4,5)");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($results as $anime) {
        echo "ID: {$anime['anime_id']} | {$anime['title']} | Image: " . 
             (strlen($anime['cover_image']) > 50 ? substr($anime['cover_image'], 0, 50) . '...' : $anime['cover_image']) . "\n";
    }
    
    echo "\n✅ All anime cover images updated with high quality URLs!\n";
    echo "Images now use 'l' suffix for larger, higher quality versions.\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    echo "Make sure your database connection details are correct.\n";
}
?>