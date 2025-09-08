<?php
// Quick database check for image URLs
$host = 'localhost';
$dbname = 'animeverse';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "📊 Current Anime Image URLs in Database:\n\n";
    
    $stmt = $pdo->prepare("SELECT anime_id, title, cover_image FROM anime ORDER BY anime_id");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($results as $anime) {
        echo "ID: {$anime['anime_id']}\n";
        echo "Title: {$anime['title']}\n";
        echo "Image URL: {$anime['cover_image']}\n";
        echo "URL Length: " . strlen($anime['cover_image']) . " characters\n";
        echo "---\n";
    }
    
    echo "\n🧪 Testing Image URL Accessibility:\n\n";
    
    foreach ($results as $anime) {
        if (!empty($anime['cover_image'])) {
            $url = $anime['cover_image'];
            $headers = @get_headers($url);
            $status = $headers ? $headers[0] : 'Unable to fetch headers';
            
            echo "Testing: {$anime['title']}\n";
            echo "URL: $url\n";
            echo "Status: $status\n";
            echo "---\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}
?>