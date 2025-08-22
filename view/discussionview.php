<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discussion Channel</title>
    <link rel="stylesheet" href="/animeverse/css/discussion.css">
</head>
<body>
<!-- Navigation Bar -->
<nav class="navbar">
    <div class="nav-container">
        <a class="nav-logo">AnimeVerse</a>
        <ul class="nav-links">
            <li><a href="/animeverse/controller/homecontroller.php">Home</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1>Discussion Channel</h1>

    
    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    
    <form method="get">
        <label for="genre">Select Genre:</label>
        <select name="genre_id" id="genre" onchange="this.form.submit()">
            <option value="0">--Choose a genre--</option>
            <?php foreach ($genres as $genre): ?>
                <option value="<?= $genre['genre_id'] ?>"
                    <?= ($selected_genre == $genre['genre_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($genre['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($selected_genre > 0): ?>
        
        <h2>Create a Thread</h2>
        <form method="post">
            <input type="hidden" name="genre_id" value="<?= $selected_genre ?>">
            <p>
                <label for="title">Title:</label><br>
                <input type="text" name="title" id="title" required>
            </p>
            <p>
                <label for="content">Content:</label><br>
                <textarea name="content" id="content" rows="5" required></textarea>
            </p>
            <button type="submit">Post Thread</button>
        </form>

        <h2>Threads</h2>
        <?php if (!empty($threads)): ?>
            <ul class="thread-list">
                <?php foreach ($threads as $thread): ?>
                    <li class="thread-item">
                        <div class="thread-title"><?= htmlspecialchars($thread['title']) ?></div>
                        <div class="thread-meta">
                            by <?= htmlspecialchars($thread['username']) ?> 
                            <em>(<?= $thread['posted_at'] ?>)</em>
                        </div>
                        <div class="thread-content"><?= nl2br(htmlspecialchars($thread['content'])) ?></div>

                        
                        <form method="post" class="like-form">
                            <input type="hidden" name="like_discussion_id" value="<?= $thread['discussion_id'] ?>">
                            <button type="submit" name="is_like" value="1">👍 <?= $thread['likes'] ?? 0 ?></button>
                            <button type="submit" name="is_like" value="0">👎 <?= $thread['dislikes'] ?? 0 ?></button>
                        </form>

                       
                        <div class="replies">
                            <h4>Replies</h4>
                            <?php 
                                $replies = $discussionModel->getReplies($thread['discussion_id']); 
                                if (!empty($replies)):
                            ?>
                                <ul>
                                    <?php foreach ($replies as $reply): ?>
                                        <li>
                                            <strong><?= htmlspecialchars($reply['username']) ?></strong>:
                                            <?= nl2br(htmlspecialchars($reply['content'])) ?>
                                            <em>(<?= $reply['replied_at'] ?>)</em>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p>No replies yet.</p>
                            <?php endif; ?>

                          
                            <form method="post" class="reply-form">
                                <input type="hidden" name="reply_discussion_id" value="<?= $thread['discussion_id'] ?>">
                                <textarea name="reply_content" rows="2" placeholder="Write a reply..." required></textarea>
                                <button type="submit">Reply</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No threads yet in this genre. Be the first to post!</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
