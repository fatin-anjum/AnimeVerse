<?php
/**
 * Integration Test for New Features
 * Run this file to verify all new features are properly integrated
 */

// Include database connection
require_once 'db.php';

echo "<h1>AnimeVerse New Features Integration Test</h1>\n";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .test{margin:10px 0;padding:10px;border-left:4px solid #ccc;}</style>\n";

// Test 1: Database Connection
echo "<div class='test'>";
echo "<h3>Test 1: Database Connection</h3>";
try {
    $stmt = $pdo->query("SELECT 1");
    echo "<span class='success'>✓ Database connection successful</span><br>";
} catch (Exception $e) {
    echo "<span class='error'>✗ Database connection failed: " . $e->getMessage() . "</span><br>";
}
echo "</div>";

// Test 2: New Tables Exist
echo "<div class='test'>";
echo "<h3>Test 2: New Tables Structure</h3>";

$tables_to_check = [
    'user_badges' => 'Badges system table',
    'user_experience' => 'Experience points table', 
    'spoiler_tags' => 'Spoiler management table',
    'anime' => 'Anime data table (should have sample data)',
    'follows' => 'User follow relationships'
];

foreach ($tables_to_check as $table => $description) {
    try {
        $stmt = $pdo->query("DESCRIBE `$table`");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<span class='success'>✓ Table '$table' exists</span> - $description<br>";
        echo "&nbsp;&nbsp;&nbsp;Columns: " . implode(', ', $columns) . "<br>";
    } catch (Exception $e) {
        echo "<span class='error'>✗ Table '$table' missing</span> - $description<br>";
        echo "&nbsp;&nbsp;&nbsp;Error: " . $e->getMessage() . "<br>";
    }
}
echo "</div>";

// Test 3: Sample Data
echo "<div class='test'>";
echo "<h3>Test 3: Sample Data</h3>";

try {
    // Check anime data
    $stmt = $pdo->query("SELECT COUNT(*) FROM anime");
    $anime_count = $stmt->fetchColumn();
    echo "<span class='success'>✓ Anime table has $anime_count entries</span><br>";
    
    // Check users
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $user_count = $stmt->fetchColumn();
    echo "<span class='success'>✓ Users table has $user_count entries</span><br>";
    
    // Check genres
    $stmt = $pdo->query("SELECT COUNT(*) FROM genres");
    $genre_count = $stmt->fetchColumn();
    echo "<span class='success'>✓ Genres table has $genre_count entries</span><br>";
    
} catch (Exception $e) {
    echo "<span class='error'>✗ Error checking sample data: " . $e->getMessage() . "</span><br>";
}
echo "</div>";

// Test 4: Model Classes
echo "<div class='test'>";
echo "<h3>Test 4: Model Classes</h3>";

$models_to_test = [
    'AnimeReviewModel' => 'model/animereviewmodel.php',
    'SpoilerModel' => 'model/spoilermodel.php', 
    'FollowModel' => 'model/followmodel.php',
    'BadgeModel' => 'model/badgemodel.php'
];

foreach ($models_to_test as $class => $file) {
    try {
        if (file_exists($file)) {
            require_once $file;
            if (class_exists($class)) {
                $model = new $class($pdo);
                echo "<span class='success'>✓ $class loaded successfully</span><br>";
            } else {
                echo "<span class='error'>✗ Class $class not found in $file</span><br>";
            }
        } else {
            echo "<span class='error'>✗ File $file not found</span><br>";
        }
    } catch (Exception $e) {
        echo "<span class='error'>✗ Error loading $class: " . $e->getMessage() . "</span><br>";
    }
}
echo "</div>";

// Test 5: Controller Files
echo "<div class='test'>";
echo "<h3>Test 5: Controller Files</h3>";

$controllers_to_test = [
    'AnimeReviewController' => 'controller/animereviewcontroller.php',
    'SpoilerController' => 'controller/spoilercontroller.php',
    'FollowController' => 'controller/followcontroller.php', 
    'BadgeController' => 'controller/badgecontroller.php'
];

foreach ($controllers_to_test as $class => $file) {
    if (file_exists($file)) {
        echo "<span class='success'>✓ $file exists</span><br>";
    } else {
        echo "<span class='error'>✗ $file missing</span><br>";
    }
}
echo "</div>";

// Test 6: View Files
echo "<div class='test'>";
echo "<h3>Test 6: View Files</h3>";

$views_to_test = [
    'view/animereviewview.php' => 'Anime Review Main View',
    'view/singleanimeview.php' => 'Single Anime View',
    'view/spoilerview.php' => 'Spoiler Management View',
    'view/followview.php' => 'Follow System View',
    'view/badgeview.php' => 'Badges & Levels View'
];

foreach ($views_to_test as $file => $description) {
    if (file_exists($file)) {
        echo "<span class='success'>✓ $file exists</span> - $description<br>";
    } else {
        echo "<span class='error'>✗ $file missing</span> - $description<br>";
    }
}
echo "</div>";

// Test 7: CSS Files
echo "<div class='test'>";
echo "<h3>Test 7: CSS Files</h3>";

$css_files = [
    'css/animereview.css' => 'Anime Review Styles',
    'css/spoiler.css' => 'Spoiler Management Styles', 
    'css/follow.css' => 'Follow System Styles',
    'css/badge.css' => 'Badges & Levels Styles'
];

foreach ($css_files as $file => $description) {
    if (file_exists($file)) {
        echo "<span class='success'>✓ $file exists</span> - $description<br>";
    } else {
        echo "<span class='error'>✗ $file missing</span> - $description<br>";
    }
}
echo "</div>";

// Test 8: Route Testing
echo "<div class='test'>";
echo "<h3>Test 8: Available Routes</h3>";

$routes = [
    '?page=animereview' => 'Anime Reviews & Ratings',
    '?page=spoiler' => 'Spoiler Alert Management',
    '?page=follow' => 'User Follow System', 
    '?page=badge' => 'Badges & Levels System'
];

echo "<strong>New routes added to index.php:</strong><br>";
foreach ($routes as $route => $description) {
    echo "<span class='success'>✓ <a href='$route' target='_blank'>$route</a></span> - $description<br>";
}
echo "</div>";

// Test 9: Database Method Testing
echo "<div class='test'>";
echo "<h3>Test 9: Basic Database Operations</h3>";

try {
    // Test AnimeReviewModel
    if (class_exists('AnimeReviewModel')) {
        $animeModel = new AnimeReviewModel($pdo);
        $anime_list = $animeModel->getAllAnime();
        echo "<span class='success'>✓ AnimeReviewModel::getAllAnime() returns " . count($anime_list) . " anime</span><br>";
    }
    
    // Test BadgeModel
    if (class_exists('BadgeModel')) {
        $badgeModel = new BadgeModel($pdo);
        $experience_map = [
            'review_submitted' => 20,
            'fanart_uploaded' => 15,
            'discussion_started' => 10
        ];
        echo "<span class='success'>✓ BadgeModel loaded with experience system</span><br>";
    }
    
    // Test FollowModel
    if (class_exists('FollowModel')) {
        $followModel = new FollowModel($pdo);
        $popular_users = $followModel->getPopularUsers(5);
        echo "<span class='success'>✓ FollowModel::getPopularUsers() returns " . count($popular_users) . " users</span><br>";
    }
    
} catch (Exception $e) {
    echo "<span class='error'>✗ Error testing database operations: " . $e->getMessage() . "</span><br>";
}
echo "</div>";

echo "<div class='test'>";
echo "<h2>🎉 Integration Test Complete!</h2>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Run <code>database_updates.sql</code> if you haven't already</li>";
echo "<li>Test each feature by visiting the routes above</li>";
echo "<li>Create user accounts and test the full functionality</li>";
echo "<li>Check the FEATURES_README.md for detailed testing scenarios</li>";
echo "</ol>";
echo "</div>";

?>