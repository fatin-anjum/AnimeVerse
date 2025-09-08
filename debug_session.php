<?php
session_start();

echo "<h2>Session Debug Information</h2>";
echo "<h3>Current Session Variables:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>Session ID:</h3>";
echo session_id();

echo "<h3>Clear Session (click to logout):</h3>";
if (isset($_GET['clear'])) {
    session_destroy();
    echo "<p style='color: green;'>Session cleared! <a href='index.php'>Go to main page</a></p>";
} else {
    echo "<a href='?clear=1'>Clear Session</a>";
}

echo "<br><br><a href='index.php'>Back to main page</a>";
?>