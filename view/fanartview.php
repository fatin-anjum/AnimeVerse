<?php

$message = $message ?? '';
$fanarts = $fanarts ?? [];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fan Art</title>
    <link rel="stylesheet" href="/animeverse/css/fanart.css">
</head>
<body>
    <nav class="navbar">
        <a href="/animeverse/controller/homecontroller.php">Home</a>
        <a href="/animeverse/controller/profilecontroller.php">Profile</a>
        <a href="/animeverse/controller/logoutcontroller.php">Logout</a>
    </nav>

    <div class="container">
        <h1>Upload Fan Art</h1>
        <?php if($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="description" placeholder="Description"></textarea>
            <input type="file" name="fanart_file" accept="image/*" required>
            <button type="submit">Upload</button>
        </form>

        <h1>All Fan Art</h1>
        <?php if (!empty($fanarts)): ?>
            <?php foreach($fanarts as $art): ?>
                <div class="fanart-card">
                    <h2><?= htmlspecialchars($art['title']) ?> - <?= htmlspecialchars($art['username'] ?? 'Unknown') ?></h2>
                    
                    <?php if (!empty($art['filename']) && file_exists(__DIR__ . '/../uploads/' . $art['filename'])): ?>
                        <img src="/animeverse/uploads/<?= htmlspecialchars($art['filename']) ?>" alt="<?= htmlspecialchars($art['title']) ?>">
                    <?php else: ?>
                        <p>No image available</p>
                    <?php endif; ?>

                    <p><?= htmlspecialchars($art['description'] ?? '') ?></p>

                    
                    <form method="post" class="inline-form">
                        <input type="hidden" name="heart_fanart_id" value="<?= $art['fanart_id'] ?>">
                        <button type="submit">❤️ <?= $art['hearts'] ?? 0 ?></button>
                    </form>

                    
                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] === $art['user_id']): ?>
                        <form method="post" class="inline-form">
                            <input type="hidden" name="delete_fanart_id" value="<?= $art['fanart_id'] ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    <?php endif; ?>

                   
                    <h3>Comments:</h3>
                    <?php if(!empty($art['comments'])): ?>
                        <?php foreach($art['comments'] as $comment): ?>
                            <p><strong><?= htmlspecialchars($comment['username'] ?? 'Unknown') ?>:</strong> <?= htmlspecialchars($comment['comment'] ?? '') ?></p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No comments yet.</p>
                    <?php endif; ?>

                    <form method="post">
                        <input type="hidden" name="fanart_id" value="<?= $art['fanart_id'] ?>">
                        <input type="text" name="comment" placeholder="Add a comment" required>
                        <button type="submit">Comment</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No fan art has been uploaded yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
