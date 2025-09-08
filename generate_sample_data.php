<?php
/**
 * Sample Data Generator for Reviews and Ratings
 * Run this script to populate the database with sample reviews and ratings
 * This will make the anime review system active and demonstrate functionality
 */

// Include database connection
require_once 'db.php';
require_once 'model/animereviewmodel.php';
require_once 'model/badgemodel.php';

echo "<h1>🎌 AnimeVerse Sample Data Generator</h1>\n";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>\n";

try {
    // Initialize models
    $animeModel = new AnimeReviewModel($pdo);
    $badgeModel = new BadgeModel($pdo);
    
    echo "<h2>Step 1: Checking Database Structure</h2>";
    
    // Check if anime data exists
    $anime_list = $animeModel->getAllAnime();
    echo "<span class='info'>Found " . count($anime_list) . " anime entries</span><br>";
    
    if (empty($anime_list)) {
        echo "<span class='error'>⚠️ No anime data found! Please run database_updates.sql first.</span><br>";
        echo "<p>To add anime data:</p>";
        echo "<ol>";
        echo "<li>Open phpMyAdmin or MySQL client</li>";
        echo "<li>Select the 'animeverse' database</li>";
        echo "<li>Import or run the contents of 'database_updates.sql'</li>";
        echo "</ol>";
        exit;
    }
    
    // Check if users exist
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE is_active = 1");
    $user_count = $stmt->fetchColumn();
    echo "<span class='info'>Found $user_count active users</span><br>";
    
    if ($user_count == 0) {
        echo "<span class='error'>⚠️ No users found! Please register some users first.</span><br>";
        echo "<p>To add users:</p>";
        echo "<ol>";
        echo "<li>Go to <a href='?page=register' target='_blank'>Register Page</a></li>";
        echo "<li>Create at least 2-3 user accounts</li>";
        echo "<li>Then run this script again</li>";
        echo "</ol>";
        exit;
    }
    
    echo "<h2>Step 2: Creating Sample Reviews</h2>";
    
    // Get users for sample reviews
    $stmt = $pdo->query("SELECT user_id, username FROM users WHERE is_active = 1 LIMIT 5");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Sample reviews data
    $sample_reviews = [
        [
            'anime_id' => 1, // Attack on Titan
            'reviews' => [
                ['rating' => 9, 'comment' => 'Absolutely incredible series! The plot twists and character development are phenomenal. The animation quality is top-notch and the soundtrack is emotionally powerful.', 'is_spoiler' => 0],
                ['rating' => 8, 'comment' => 'Great anime with complex themes about freedom and humanity. Some parts can be quite dark and intense, but that adds to the impact.', 'is_spoiler' => 0],
                ['rating' => 10, 'comment' => 'Masterpiece! The final season was mind-blowing. The way everything connects is brilliant.', 'is_spoiler' => 1, 'spoiler_warning' => 'Contains references to final season events'],
                ['rating' => 7, 'comment' => 'Good anime but quite depressing. The titans are terrifying and the story gets very heavy.', 'is_spoiler' => 0],
            ]
        ],
        [
            'anime_id' => 2, // Demon Slayer
            'reviews' => [
                ['rating' => 9, 'comment' => 'Beautiful animation and heartwarming story about family bonds. Tanjiro is such a kind protagonist and the breathing techniques are amazing!', 'is_spoiler' => 0],
                ['rating' => 8, 'comment' => 'Stunning visuals and great fight scenes. The character relationships are well developed and emotional.', 'is_spoiler' => 0],
                ['rating' => 10, 'comment' => 'The Mugen Train arc was absolutely incredible! Made me cry multiple times.', 'is_spoiler' => 1, 'spoiler_warning' => 'References Mugen Train movie events'],
                ['rating' => 8, 'comment' => 'Really enjoying this series. The demons have interesting backstories that make you feel for them.', 'is_spoiler' => 0],
            ]
        ],
        [
            'anime_id' => 3, // One Piece
            'reviews' => [
                ['rating' => 10, 'comment' => 'The greatest adventure story ever told! Luffy and his crew never fail to inspire. The world-building is unmatched.', 'is_spoiler' => 0],
                ['rating' => 9, 'comment' => 'Epic journey with amazing character development. Each arc brings new excitement and emotional moments.', 'is_spoiler' => 0],
                ['rating' => 8, 'comment' => 'Long but worth every episode. The friendships and dreams of the crew are beautifully portrayed.', 'is_spoiler' => 0],
                ['rating' => 9, 'comment' => 'Marineford arc was absolutely insane! Still gives me chills thinking about it.', 'is_spoiler' => 1, 'spoiler_warning' => 'Contains major Marineford War spoilers'],
            ]
        ],
        [
            'anime_id' => 4, // My Hero Academia
            'reviews' => [
                ['rating' => 8, 'comment' => 'Great superhero anime with inspiring messages about perseverance and heroism. Deku is a relatable protagonist.', 'is_spoiler' => 0],
                ['rating' => 9, 'comment' => 'Love the quirks system and how creative the powers are. The school setting works really well.', 'is_spoiler' => 0],
                ['rating' => 7, 'comment' => 'Good anime but sometimes feels predictable. Still enjoyable with great action scenes.', 'is_spoiler' => 0],
                ['rating' => 8, 'comment' => 'All Might is such an amazing mentor character. The Plus Ultra spirit is contagious!', 'is_spoiler' => 0],
            ]
        ],
        [
            'anime_id' => 5, // Death Note
            'reviews' => [
                ['rating' => 10, 'comment' => 'Psychological masterpiece! The cat and mouse game between Light and L is absolutely thrilling.', 'is_spoiler' => 0],
                ['rating' => 9, 'comment' => 'Brilliant premise and execution. Makes you question morality and justice in fascinating ways.', 'is_spoiler' => 0],
                ['rating' => 8, 'comment' => 'First half is perfect, second half is still good but not as strong. L is an incredible character.', 'is_spoiler' => 1, 'spoiler_warning' => 'References events from second half of series'],
                ['rating' => 9, 'comment' => 'The mind games and intellectual battles are incredible. Light is a fascinating anti-hero.', 'is_spoiler' => 0],
            ]
        ]
    ];
    
    $reviews_added = 0;
    $errors = 0;
    
    foreach ($sample_reviews as $anime_data) {
        $anime_id = $anime_data['anime_id'];
        
        // Get anime title for display
        $anime = $animeModel->getAnimeById($anime_id);
        if (!$anime) {
            echo "<span class='error'>❌ Anime ID $anime_id not found</span><br>";
            continue;
        }
        
        echo "<h3>Adding reviews for: {$anime['title']}</h3>";
        
        foreach ($anime_data['reviews'] as $index => $review_data) {
            // Use different users for reviews (cycle through available users)
            $user = $users[$index % count($users)];
            $user_id = $user['user_id'];
            
            // Check if user already reviewed this anime
            if ($animeModel->hasUserReviewed($user_id, $anime_id)) {
                echo "<span class='info'>⏭️ User {$user['username']} already reviewed this anime, skipping...</span><br>";
                continue;
            }
            
            try {
                $success = $animeModel->addAnimeReview(
                    $user_id,
                    $anime_id, 
                    $review_data['rating'],
                    $review_data['comment'],
                    $review_data['is_spoiler'],
                    $review_data['spoiler_warning'] ?? null
                );
                
                if ($success) {
                    echo "<span class='success'>✅ Added review by {$user['username']} (Rating: {$review_data['rating']}/10)</span><br>";
                    $reviews_added++;
                    
                    // Award experience points for the review
                    $badgeModel->awardExperienceForAction($user_id, 'review_submitted');
                    
                } else {
                    echo "<span class='error'>❌ Failed to add review by {$user['username']}</span><br>";
                    $errors++;
                }
            } catch (Exception $e) {
                echo "<span class='error'>❌ Error adding review by {$user['username']}: " . $e->getMessage() . "</span><br>";
                $errors++;
            }
        }
    }
    
    echo "<h2>Step 3: Generating Additional Data</h2>";
    
    // Award some badges and experience to make the system more active
    foreach ($users as $user) {
        $user_id = $user['user_id'];
        
        // Award some additional experience points for variety
        $badgeModel->addExperience($user_id, 'fanart_uploaded', 15, 'Uploaded fanart');
        $badgeModel->addExperience($user_id, 'discussion_started', 10, 'Started discussion');
        $badgeModel->addExperience($user_id, 'login_daily', 5, 'Daily login bonus');
        
        echo "<span class='success'>✅ Added experience points for {$user['username']}</span><br>";
    }
    
    echo "<h2>🎉 Sample Data Generation Complete!</h2>";
    echo "<div style='background:#e8f5e8;padding:15px;border-radius:8px;margin:20px 0;'>";
    echo "<h3>Summary:</h3>";
    echo "<ul>";
    echo "<li><strong>$reviews_added</strong> reviews added successfully</li>";
    echo "<li><strong>$errors</strong> errors encountered</li>";
    echo "<li><strong>" . count($users) . "</strong> users received experience points</li>";
    echo "<li><strong>" . count($anime_list) . "</strong> anime available for review</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<h3>🚀 Next Steps:</h3>";
    echo "<ol>";
    echo "<li><a href='?page=animereview' target='_blank'>Visit Anime Reviews Page</a> - See the reviews in action!</li>";
    echo "<li><a href='?page=badge' target='_blank'>Check Badges & Levels</a> - See experience points and badges</li>";
    echo "<li><a href='?page=follow' target='_blank'>Try Follow System</a> - Connect with other users</li>";
    echo "<li><a href='?page=spoiler' target='_blank'>Manage Spoilers</a> - Handle spoiler content</li>";
    echo "</ol>";
    
    echo "<h3>📝 Test the System:</h3>";
    echo "<ul>";
    echo "<li>Click on any anime to see detailed reviews and ratings</li>";
    echo "<li>Login as any user and try adding your own review</li>";
    echo "<li>Check how spoiler warnings work</li>";
    echo "<li>See the rating statistics and breakdowns</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<span class='error'>❌ Fatal Error: " . $e->getMessage() . "</span><br>";
    echo "<p>Please ensure:</p>";
    echo "<ul>";
    echo "<li>XAMPP is running (Apache + MySQL)</li>";
    echo "<li>The animeverse database exists</li>";
    echo "<li>database_updates.sql has been imported</li>";
    echo "<li>At least one user account exists</li>";
    echo "</ul>";
}
?>