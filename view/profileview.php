<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - AnimeVerse</title>
    <link rel="stylesheet" href="css/profile.css">
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
            <a href="?page=profile" class="active"><i class="fas fa-user-edit"></i> Update Profile</a>
            <a href="?page=fanart"><i class="fas fa-palette"></i> Fan Art</a>
            <a href="?page=discussion"><i class="fas fa-comments"></i> Discussions</a>
            <a href="?page=collectibles"><i class="fas fa-shopping-cart"></i> Marketplace</a>
            <a href="?page=badge"><i class="fas fa-trophy"></i> Badges</a>
            <a href="?page=follow"><i class="fas fa-users"></i> Social</a>
            <a href="?page=animereview"><i class="fas fa-star"></i> Reviews</a>
            <?php if ($userEmail): ?>
                <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="profile-container">
        <!-- Page Header -->
        <header class="page-header">
            <h1><i class="fas fa-user-edit"></i> Update Profile</h1>
            <p>Manage your account settings and personal information</p>
        </header>

        <?php if (!empty($message)): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="content-layout">
            <!-- Profile Update Form -->
            <section class="form-section">
                <h2><i class="fas fa-user"></i> Profile Information</h2>
                <form method="post" class="profile-form">
                    <div class="form-group">
                        <label for="username"><i class="fas fa-user"></i> Username:</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="bio"><i class="fas fa-align-left"></i> Bio:</label>
                        <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself..."><?= htmlspecialchars($user['bio']) ?></textarea>
                    </div>

                    <button type="submit" name="update" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </form>
            </section>

            <!-- Password Change Form -->
            <section class="form-section">
                <h2><i class="fas fa-lock"></i> Change Password</h2>
                <form method="post" class="profile-form">
                    <div class="form-group">
                        <label for="current_password"><i class="fas fa-key"></i> Current Password:</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>

                    <div class="form-group">
                        <label for="new_password"><i class="fas fa-lock"></i> New Password:</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>

                    <button type="submit" name="change_password" class="btn btn-secondary">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </form>
            </section>

            <!-- Danger Zone -->
            <section class="form-section danger-zone">
                <h2><i class="fas fa-exclamation-triangle"></i> Danger Zone</h2>
                <p class="warning-text">This action cannot be undone. Please be certain.</p>
                <form method="post" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone!');">
                    <button type="submit" name="disband" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Account
                    </button>
                </form>
            </section>
        </div>
    </div>

</body>
</html>
