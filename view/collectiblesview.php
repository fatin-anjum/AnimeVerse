<?php?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collectibles Marketplace - AnimeVerse</title>
    <link rel="stylesheet" href="css/collectibles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-left">
            <span>AnimeVerse</span>
        </div>
        <div class="navbar-right">
            <a href="?page=home"><i class="fas fa-home"></i> Home</a>
            <a href="?page=myprofile"><i class="fas fa-user"></i> My Profile</a>
            <a href="?page=fanart"><i class="fas fa-palette"></i> Fan Art</a>
            
            <!-- Discussion Dropdown -->
            <div class="dropdown">
                <a href="#" class="dropdown-toggle">
                    <i class="fas fa-comments"></i> Discussions <i class="fas fa-chevron-down"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="?page=discussion"><i class="fas fa-comments"></i> Discussions</a>
                    <a href="?page=polldebate"><i class="fas fa-poll"></i> Polls</a>
                    <a href="?page=debate"><i class="fas fa-balance-scale"></i> Debates</a>
                </div>
            </div>
            
            <a href="?page=collectibles" class="active"><i class="fas fa-shopping-cart"></i> Marketplace</a>
            <a href="?page=badge"><i class="fas fa-trophy"></i> Badges</a>
            <a href="?page=follow"><i class="fas fa-users"></i> Social</a>
            <a href="?page=animereview"><i class="fas fa-star"></i> Reviews</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="collectibles-container">
        <!-- Profile-style Header -->
        <header class="page-header">
            <div class="header-cover">
                <div class="header-info">
                    <h1><i class="fas fa-shopping-cart"></i> Collectibles Marketplace</h1>
                    <p>Buy and sell amazing anime collectibles</p>
                </div>
            </div>
        </header>

        <!-- Add New Collectible Button -->
        <div class="add-collectible-btn">
            <a href="index.php?page=collectibles&action=add">
                <i class="fas fa-plus"></i> Add New Collectible to Market
            </a>
        </div>

        <?php if (($_GET['action'] ?? '') === 'add'): ?>
            <div class="add-form">
                <form method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td><i class="fas fa-tag"></i> Title:</td>
                            <td><input type="text" name="title" placeholder="Enter product title" required></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-dollar-sign"></i> Price:</td>
                            <td><input type="text" name="price" placeholder="Enter price in TK" required></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-align-left"></i> Description:</td>
                            <td><textarea name="description" placeholder="Describe your collectible" rows="4"></textarea></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-image"></i> Image:</td>
                            <td><input type="file" name="image" accept="image/*" required></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button type="submit">
                                    <i class="fas fa-upload"></i> Upload Collectible
                                </button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        <?php endif; ?>

        <!-- Collectibles Grid -->
        <div class="collectibles-grid">
            <?php foreach ($collectibles as $c): ?>
                <div class="collectible">
                    <h3><i class="fas fa-gem"></i> <?= htmlspecialchars($c['title']) ?></h3>
                    <p><i class="fas fa-user"></i> <strong>Seller:</strong> <?= htmlspecialchars($c['username']) ?></p>
                    <p><i class="fas fa-money-bill-wave"></i> <strong>Price:</strong> <?= $c['price'] ?> TK</p>
                    
                    <?php if (!empty($c['description'])): ?>
                        <p><i class="fas fa-info-circle"></i> <?= htmlspecialchars($c['description']) ?></p>
                    <?php endif; ?>
                    
                    <?php if ($c['image_url']): ?>
                        <img src="<?= htmlspecialchars($c['image_url']) ?>" alt="<?= htmlspecialchars($c['title']) ?>" loading="lazy">
                    <?php endif; ?>

                    <?php if ($c['is_sold']): ?>
                        <p class="sold"><i class="fas fa-check-circle"></i> SOLD</p>
                        <p>
                            <a href="index.php?page=collectibles&action=invoice&id=<?= $c['collectible_id'] ?>" class="invoice-link">
                                <i class="fas fa-receipt"></i> View Invoice
                            </a>
                        </p>
                    <?php else: ?>
                        <div class="buy-form">
                            <h4><i class="fas fa-shopping-cart"></i> Purchase This Item</h4>
                            <form method="post" action="index.php?page=collectibles&action=buy&id=<?= $c['collectible_id'] ?>">
                                <table>
                                    <tr>
                                        <td><i class="fas fa-user"></i> Your Name:</td>
                                        <td><input type="text" name="buyer_name" placeholder="Enter your full name" required></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fas fa-phone"></i> Contact:</td>
                                        <td><input type="text" name="contact" placeholder="Phone or email" required></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fas fa-map-marker-alt"></i> Location:</td>
                                        <td><input type="text" name="location" placeholder="Your delivery address" required></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <button type="submit">
                                                <i class="fas fa-credit-card"></i> Buy Now
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
