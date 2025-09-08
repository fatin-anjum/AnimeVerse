<?php
require_once 'db.php';

echo "<h1>🆔 Content ID Assignment for Spoiler Control</h1>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
.section { margin: 30px 0; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
.success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0; }
.warning { background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin: 10px 0; }
.error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 10px 0; }
table { width: 100%; border-collapse: collapse; margin: 15px 0; }
th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
th { background: #f8f9fa; font-weight: bold; }
tr:nth-child(even) { background: #f9f9f9; }
.btn { display: inline-block; background: #007bff; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; margin: 5px; }
.btn:hover { background: #0056b3; }
.spoiler-btn { background: #ffc107; color: #212529; }
.unspoiler-btn { background: #6c757d; color: white; }
</style>";

echo "<div class='container'>";

try {
    // Get all existing fanart with IDs
    echo "<div class='section'>";
    echo "<h2>📸 Your Fanart Content</h2>";
    $stmt = $pdo->query("
        SELECT f.fanart_id, f.title, f.description, f.is_spoiler, f.spoiler_warning, 
               u.username, f.created_at
        FROM fanart f 
        JOIN users u ON f.user_id = u.user_id 
        ORDER BY f.created_at DESC
    ");
    $fanarts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($fanarts)) {
        echo "<p>Found " . count($fanarts) . " fanart items. You can control spoilers for each using their IDs:</p>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Title</th><th>User</th><th>Spoiler Status</th><th>Actions</th></tr>";
        foreach ($fanarts as $art) {
            $spoiler_status = $art['is_spoiler'] ? 
                "<span style='color: #dc3545;'>⚠️ SPOILER</span>" : 
                "<span style='color: #28a745;'>✅ Safe</span>";
            
            echo "<tr>";
            echo "<td><strong>{$art['fanart_id']}</strong></td>";
            echo "<td>" . htmlspecialchars($art['title']) . "</td>";
            echo "<td>" . htmlspecialchars($art['username']) . "</td>";
            echo "<td>$spoiler_status";
            if ($art['spoiler_warning']) {
                echo "<br><em>" . htmlspecialchars($art['spoiler_warning']) . "</em>";
            }
            echo "</td>";
            echo "<td>";
            if ($art['is_spoiler']) {
                echo "<a href='?action=unspoiler&type=fanart&id={$art['fanart_id']}' class='btn unspoiler-btn'>Remove Spoiler</a>";
            } else {
                echo "<a href='?action=spoiler&type=fanart&id={$art['fanart_id']}' class='btn spoiler-btn'>Mark as Spoiler</a>";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='warning'>No fanart found in the database.</p>";
    }
    echo "</div>";

    // Get all existing discussions with IDs
    echo "<div class='section'>";
    echo "<h2>💬 Your Discussion Content</h2>";
    $stmt = $pdo->query("
        SELECT gd.discussion_id, gd.title, gd.content, gd.is_spoiler, gd.spoiler_warning,
               u.username, g.name as genre_name, gd.posted_at
        FROM genre_discussions gd 
        JOIN users u ON gd.user_id = u.user_id 
        LEFT JOIN genres g ON gd.genre_id = g.genre_id
        ORDER BY gd.posted_at DESC
    ");
    $discussions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($discussions)) {
        echo "<p>Found " . count($discussions) . " discussion items. You can control spoilers for each using their IDs:</p>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Title</th><th>Genre</th><th>User</th><th>Spoiler Status</th><th>Actions</th></tr>";
        foreach ($discussions as $disc) {
            $spoiler_status = $disc['is_spoiler'] ? 
                "<span style='color: #dc3545;'>⚠️ SPOILER</span>" : 
                "<span style='color: #28a745;'>✅ Safe</span>";
            
            echo "<tr>";
            echo "<td><strong>{$disc['discussion_id']}</strong></td>";
            echo "<td>" . htmlspecialchars($disc['title']) . "</td>";
            echo "<td>" . htmlspecialchars($disc['genre_name'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($disc['username']) . "</td>";
            echo "<td>$spoiler_status";
            if ($disc['spoiler_warning']) {
                echo "<br><em>" . htmlspecialchars($disc['spoiler_warning']) . "</em>";
            }
            echo "</td>";
            echo "<td>";
            if ($disc['is_spoiler']) {
                echo "<a href='?action=unspoiler&type=discussion&id={$disc['discussion_id']}' class='btn unspoiler-btn'>Remove Spoiler</a>";
            } else {
                echo "<a href='?action=spoiler&type=discussion&id={$disc['discussion_id']}' class='btn spoiler-btn'>Mark as Spoiler</a>";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='warning'>No discussions found in the database.</p>";
    }
    echo "</div>";

    // Get all existing debates with IDs
    echo "<div class='section'>";
    echo "<h2>🗣️ Your Debate Content</h2>";
    $stmt = $pdo->query("
        SELECT d.debate_id, d.title, d.content, d.is_spoiler, d.spoiler_warning,
               u.username, d.created_at
        FROM debates d 
        JOIN users u ON d.user_id = u.user_id 
        ORDER BY d.created_at DESC
    ");
    $debates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($debates)) {
        echo "<p>Found " . count($debates) . " debate items. You can control spoilers for each using their IDs:</p>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Title</th><th>User</th><th>Spoiler Status</th><th>Actions</th></tr>";
        foreach ($debates as $debate) {
            $spoiler_status = $debate['is_spoiler'] ? 
                "<span style='color: #dc3545;'>⚠️ SPOILER</span>" : 
                "<span style='color: #28a745;'>✅ Safe</span>";
            
            echo "<tr>";
            echo "<td><strong>{$debate['debate_id']}</strong></td>";
            echo "<td>" . htmlspecialchars($debate['title']) . "</td>";
            echo "<td>" . htmlspecialchars($debate['username']) . "</td>";
            echo "<td>$spoiler_status";
            if ($debate['spoiler_warning']) {
                echo "<br><em>" . htmlspecialchars($debate['spoiler_warning']) . "</em>";
            }
            echo "</td>";
            echo "<td>";
            if ($debate['is_spoiler']) {
                echo "<a href='?action=unspoiler&type=debate&id={$debate['debate_id']}' class='btn unspoiler-btn'>Remove Spoiler</a>";
            } else {
                echo "<a href='?action=spoiler&type=debate&id={$debate['debate_id']}' class='btn spoiler-btn'>Mark as Spoiler</a>";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='warning'>No debates found in the database.</p>";
    }
    echo "</div>";

    // Handle spoiler actions
    if (isset($_GET['action']) && isset($_GET['type']) && isset($_GET['id'])) {
        $action = $_GET['action'];
        $type = $_GET['type'];
        $id = (int) $_GET['id'];
        
        echo "<div class='section'>";
        echo "<h2>🎯 Spoiler Action Results</h2>";
        
        if ($action === 'spoiler') {
            // Mark as spoiler - get warning from user
            if (isset($_POST['spoiler_warning'])) {
                $warning = $_POST['spoiler_warning'];
                
                switch ($type) {
                    case 'fanart':
                        $stmt = $pdo->prepare("UPDATE fanart SET is_spoiler = 1, spoiler_warning = ? WHERE fanart_id = ?");
                        break;
                    case 'discussion':
                        $stmt = $pdo->prepare("UPDATE genre_discussions SET is_spoiler = 1, spoiler_warning = ? WHERE discussion_id = ?");
                        break;
                    case 'debate':
                        $stmt = $pdo->prepare("UPDATE debates SET is_spoiler = 1, spoiler_warning = ? WHERE debate_id = ?");
                        break;
                }
                
                if ($stmt->execute([$warning, $id])) {
                    echo "<div class='success'>✅ Successfully marked {$type} ID {$id} as spoiler with warning: " . htmlspecialchars($warning) . "</div>";
                } else {
                    echo "<div class='error'>❌ Failed to mark {$type} ID {$id} as spoiler.</div>";
                }
            } else {
                // Show spoiler warning form
                echo "<h3>Mark {$type} ID {$id} as Spoiler</h3>";
                echo "<form method='post'>";
                echo "<label for='spoiler_warning'>Spoiler Warning Message:</label><br>";
                echo "<input type='text' name='spoiler_warning' placeholder='e.g., Contains major plot spoilers' required style='width: 400px; padding: 8px; margin: 10px 0;'><br>";
                echo "<button type='submit' class='btn spoiler-btn'>Apply Spoiler Warning</button>";
                echo "<a href='?' class='btn'>Cancel</a>";
                echo "</form>";
            }
        } elseif ($action === 'unspoiler') {
            // Remove spoiler
            switch ($type) {
                case 'fanart':
                    $stmt = $pdo->prepare("UPDATE fanart SET is_spoiler = 0, spoiler_warning = NULL WHERE fanart_id = ?");
                    break;
                case 'discussion':
                    $stmt = $pdo->prepare("UPDATE genre_discussions SET is_spoiler = 0, spoiler_warning = NULL WHERE discussion_id = ?");
                    break;
                case 'debate':
                    $stmt = $pdo->prepare("UPDATE debates SET is_spoiler = 0, spoiler_warning = NULL WHERE debate_id = ?");
                    break;
            }
            
            if ($stmt->execute([$id])) {
                echo "<div class='success'>✅ Successfully removed spoiler warning from {$type} ID {$id}.</div>";
            } else {
                echo "<div class='error'>❌ Failed to remove spoiler from {$type} ID {$id}.</div>";
            }
        }
        echo "</div>";
    }

    // Summary section
    echo "<div class='section'>";
    echo "<h2>📊 Spoiler Control Summary</h2>";
    
    $stmt = $pdo->query("
        SELECT 
            (SELECT COUNT(*) FROM fanart WHERE is_spoiler = 1) as spoiler_fanart,
            (SELECT COUNT(*) FROM fanart WHERE is_spoiler = 0) as safe_fanart,
            (SELECT COUNT(*) FROM genre_discussions WHERE is_spoiler = 1) as spoiler_discussions,
            (SELECT COUNT(*) FROM genre_discussions WHERE is_spoiler = 0) as safe_discussions,
            (SELECT COUNT(*) FROM debates WHERE is_spoiler = 1) as spoiler_debates,
            (SELECT COUNT(*) FROM debates WHERE is_spoiler = 0) as safe_debates
    ");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<table>";
    echo "<tr><th>Content Type</th><th>Safe Content</th><th>Spoiler Content</th><th>Total</th></tr>";
    echo "<tr><td>Fanart</td><td>{$stats['safe_fanart']}</td><td>{$stats['spoiler_fanart']}</td><td>" . ($stats['safe_fanart'] + $stats['spoiler_fanart']) . "</td></tr>";
    echo "<tr><td>Discussions</td><td>{$stats['safe_discussions']}</td><td>{$stats['spoiler_discussions']}</td><td>" . ($stats['safe_discussions'] + $stats['spoiler_discussions']) . "</td></tr>";
    echo "<tr><td>Debates</td><td>{$stats['safe_debates']}</td><td>{$stats['spoiler_debates']}</td><td>" . ($stats['safe_debates'] + $stats['spoiler_debates']) . "</td></tr>";
    echo "</table>";
    
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='error'>Database Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "<div class='section'>";
echo "<h2>🔗 Navigation</h2>";
echo "<a href='index.php' class='btn'>← Back to AnimeVerse</a>";
echo "<a href='index.php?page=spoiler' class='btn'>Spoiler Management</a>";
echo "<a href='assign_content_ids.php' class='btn'>↻ Refresh This Page</a>";
echo "</div>";

echo "</div>";
?>