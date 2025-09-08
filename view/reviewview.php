<!-- view/review_view.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Reviews - AnimeVerse</title>
    <link rel="stylesheet" href="css/review.css">
    <style>
        /* Navbar */
        .navbar {
            background-color: rgba(55, 0, 0, 1);
            color: white;
            display: flex;
            justify-content: space-between;
            padding: 15px 30px;
            align-items: center;
        }
        
        .navbar-left span {
            font-size: 32px;
            font-weight: 900;
            color: #da7000c4;
            letter-spacing: 3px;
            text-transform: uppercase;
            background: linear-gradient(45deg, #ff2200, #ff5500, #ff2200);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow:
                0 0 8px #a08989ff,
                0 0 15px #9a8a8aff,
                0 0 25px #ad9393ff,
                0 0 40px #bba3a3ff,
                1px 1px 2px #7f5959ff;
            transition: text-shadow 0.8s ease;
            cursor: default;
        }
        
        .navbar-left span:hover {
            text-shadow:
                0 0 12px #472108f5,
                0 0 20px #472108f5,
                0 0 35px #472108f5,
                0 0 50px #472108f5,
                2px 2px 4px #aa2200;
        }
        
        .navbar-right a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
            transition: color 0.3s ease;
        }
        
        .navbar-right a:hover {
            text-decoration: underline;
            color: #ff4d4d;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <span>A n i m e V e r s e</span>
        </div>
        <div class="navbar-right">
            <a href="?page=home">Home</a>
            <a href="?page=myprofile">My Profile</a>
            <a href="?page=profile">Profile Management</a>
            <a href="?page=fanart">Fan Arts</a>
            <a href="?page=discussion">Discussion Channel</a>
            <a href="?page=polldebate">Polls</a>
            <a href="?page=debate">Debates</a>
            <a href="?page=animereview">Ratings/Reviews</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="?page=logout">Logout</a>
            <?php else: ?>
                <a href="?page=login">Login</a>
            <?php endif; ?>
        </div>
    </div>
<div class="review-container">
    <!-- Header -->
    <div class="review-header">
        <h3>Ratings & Reviews</h3>
    </div>

    <!-- Review Form -->
    <?php if (isset($currentUser)): ?>
        <form class="review-form" action="?page=submit_review" method="POST">
            <input type="hidden" name="item_id" value="<?= htmlspecialchars($itemId) ?>">
            <textarea name="review_text" placeholder="Write your review..." required></textarea>
            <label>
                Rating: 
                <input type="number" name="rating" min="1" max="5" required>
            </label>
            <button type="submit">Submit</button>
        </form>
    <?php else: ?>
        <p>Please <a href="?page=login">login</a> to submit a review.</p>
    <?php endif; ?>

    <!-- List of Reviews -->
    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review-item">
                <div class="review-user"><?= htmlspecialchars($review['username']) ?></div>
                <div class="review-rating">
                    <?php
                    for ($i = 0; $i < $review['rating']; $i++) {
                        echo '★';
                    }
                    for ($i = $review['rating']; $i < 5; $i++) {
                        echo '☆';
                    }
                    ?>
                </div>
                <div class="review-text"><?= nl2br(htmlspecialchars($review['review_text'])) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No reviews yet. Be the first to review!</p>
    <?php endif; ?>
</div>
</body>
</html>
