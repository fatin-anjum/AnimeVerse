<?php
require_once 'db.php';

echo "<h1>🔍 Content ID Validation Report</h1>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
.success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0; }
.info { background: #d1ecf1; color: #0c5460; padding: 10px; border-radius: 5px; margin: 10px 0; }
.warning { background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin: 10px 0; }
table { width: 100%; border-collapse: collapse; margin: 15px 0; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background: #f8f9fa; }
.id-column { font-weight: bold; color: #007bff; }
</style>";

echo "<div class='container'>";

try {
    // Check fanart IDs
    echo "<h2>📸 Fanart ID Validation</h2>";
    $stmt = $pdo->query("SELECT fanart_id, title, is_spoiler, user_id FROM fanart ORDER BY fanart_id");
    $fanarts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($fanarts)) {
        echo "<div class='success'>✅ Found " . count($fanarts) . " fanart items with assigned IDs</div>";
        echo "<table><tr><th>ID</th><th>Title</th><th>User ID</th><th>Spoiler Ready</th></tr>";
        foreach ($fanarts as $art) {
            $spoiler_ready = (isset($art['is_spoiler']) && $art['is_spoiler'] !== null) ? "✅ Yes" : "⚠️ Column missing";
            echo "<tr>";
            echo "<td class='id-column'>{$art['fanart_id']}</td>";
            echo "<td>" . htmlspecialchars($art['title']) . "</td>";
            echo "<td>{$art['user_id']}</td>";
            echo "<td>$spoiler_ready</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='info'>ℹ️ No fanart found in database</div>";
    }

    // Check discussion IDs
    echo "<h2>💬 Discussion ID Validation</h2>";
    $stmt = $pdo->query("SELECT discussion_id, title, is_spoiler, user_id FROM genre_discussions ORDER BY discussion_id");
    $discussions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($discussions)) {
        echo "<div class='success'>✅ Found " . count($discussions) . " discussion items with assigned IDs</div>";
        echo "<table><tr><th>ID</th><th>Title</th><th>User ID</th><th>Spoiler Ready</th></tr>";
        foreach ($discussions as $disc) {
            $spoiler_ready = (isset($disc['is_spoiler']) && $disc['is_spoiler'] !== null) ? "✅ Yes" : "⚠️ Column missing";
            echo "<tr>";
            echo "<td class='id-column'>{$disc['discussion_id']}</td>";
            echo "<td>" . htmlspecialchars($disc['title']) . "</td>";
            echo "<td>{$disc['user_id']}</td>";
            echo "<td>$spoiler_ready</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='info'>ℹ️ No discussions found in database</div>";
    }

    // Check debate IDs
    echo "<h2>🗣️ Debate ID Validation</h2>";
    $stmt = $pdo->query("SELECT debate_id, title, is_spoiler, user_id FROM debates ORDER BY debate_id");
    $debates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($debates)) {
        echo "<div class='success'>✅ Found " . count($debates) . " debate items with assigned IDs</div>";
        echo "<table><tr><th>ID</th><th>Title</th><th>User ID</th><th>Spoiler Ready</th></tr>";
        foreach ($debates as $debate) {
            $spoiler_ready = (isset($debate['is_spoiler']) && $debate['is_spoiler'] !== null) ? "✅ Yes" : "⚠️ Column missing";
            echo "<tr>";
            echo "<td class='id-column'>{$debate['debate_id']}</td>";
            echo "<td>" . htmlspecialchars($debate['title']) . "</td>";
            echo "<td>{$debate['user_id']}</td>";
            echo "<td>$spoiler_ready</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='info'>ℹ️ No debates found in database</div>";
    }

    // Check review IDs
    echo "<h2>⭐ Review ID Validation</h2>";
    $stmt = $pdo->query("SELECT review_id, rating, is_spoiler, user_id FROM reviews ORDER BY review_id");
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($reviews)) {
        echo "<div class='success'>✅ Found " . count($reviews) . " review items with assigned IDs</div>";
        echo "<table><tr><th>ID</th><th>Rating</th><th>User ID</th><th>Spoiler Ready</th></tr>";
        foreach ($reviews as $review) {
            $spoiler_ready = (isset($review['is_spoiler']) && $review['is_spoiler'] !== null) ? "✅ Yes" : "⚠️ Column missing";
            echo "<tr>";
            echo "<td class='id-column'>{$review['review_id']}</td>";
            echo "<td>{$review['rating']}/10</td>";
            echo "<td>{$review['user_id']}</td>";
            echo "<td>$spoiler_ready</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='info'>ℹ️ No reviews found in database</div>";
    }

    // Summary
    echo "<h2>📊 Summary</h2>";
    $total_fanart = count($fanarts);
    $total_discussions = count($discussions);
    $total_debates = count($debates);
    $total_reviews = count($reviews);
    $total_content = $total_fanart + $total_discussions + $total_debates + $total_reviews;
    
    echo "<div class='success'>";
    echo "<strong>✅ ID Assignment Complete!</strong><br>";
    echo "Total Content with IDs: <strong>$total_content</strong><br>";
    echo "• Fanart: $total_fanart items<br>";
    echo "• Discussions: $total_discussions items<br>";
    echo "• Debates: $total_debates items<br>";
    echo "• Reviews: $total_reviews items<br>";
    echo "</div>";

    // Check spoiler columns
    echo "<h2>🔧 Database Structure Check</h2>";
    $tables_to_check = ['fanart', 'genre_discussions', 'debates', 'reviews'];
    $spoiler_ready = true;
    
    foreach ($tables_to_check as $table) {
        try {
            $stmt = $pdo->query("SHOW COLUMNS FROM `$table` LIKE 'is_spoiler'");
            $has_spoiler = $stmt->fetch() !== false;
            
            $stmt = $pdo->query("SHOW COLUMNS FROM `$table` LIKE 'spoiler_warning'");
            $has_warning = $stmt->fetch() !== false;
            
            if ($has_spoiler && $has_warning) {
                echo "<div class='success'>✅ Table '$table' has spoiler columns</div>";
            } else {
                echo "<div class='warning'>⚠️ Table '$table' missing spoiler columns</div>";
                $spoiler_ready = false;
            }
        } catch (Exception $e) {
            echo "<div class='warning'>⚠️ Could not check table '$table'</div>";
        }
    }

    if ($spoiler_ready) {
        echo "<div class='success'>";
        echo "<strong>🎉 System Ready for Spoiler Management!</strong><br>";
        echo "All content has IDs and spoiler columns are in place.<br>";
        echo "<a href='assign_content_ids.php' style='color: #007bff;'>→ Manage Content Spoilers</a><br>";
        echo "<a href='index.php?page=spoiler' style='color: #007bff;'>→ Spoiler Dashboard</a>";
        echo "</div>";
    }

} catch (Exception $e) {
    echo "<div class='error'>❌ Database Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "</div>";
?>