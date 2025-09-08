<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badges & Levels - AnimeVerse</title>
    <link rel="stylesheet" href="css/badge.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-left">
            <span>AnimeVerse</span>
        </div>
        <div class="navbar-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="?page=home"><i class="fas fa-home"></i> Home</a>
                <a href="?page=myprofile"><i class="fas fa-user"></i> My Profile</a>
                <a href="?page=badge" class="active"><i class="fas fa-trophy"></i> Badges</a>
                <a href="?page=follow"><i class="fas fa-users"></i> Follow</a>
                <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
                <a href="?page=register"><i class="fas fa-user-plus"></i> Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="badge-container">
        <header class="page-header">
            <h1><i class="fas fa-trophy"></i> Badges & Levels</h1>
            <p>Track your progress and achievements in the AnimeVerse community</p>
        </header>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="content-grid">
            <!-- Main Content -->
            <main class="main-content">
                <!-- Level Progress Section -->
                <section class="level-section">
                    <h2>Your Level Progress</h2>
                    <div class="level-card">
                        <div class="level-info">
                            <div class="current-level">
                                <span class="level-number"><?= $level_progress['current_level'] ?></span>
                                <span class="level-label">Current Level</span>
                            </div>
                            <div class="experience-info">
                                <div class="exp-numbers">
                                    <span class="current-exp"><?= number_format($level_progress['total_experience']) ?></span>
                                    <span class="exp-separator">/</span>
                                    <span class="next-level-exp"><?= number_format($level_progress['next_level_exp']) ?></span>
                                    <span class="exp-label">XP</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?= $level_progress['progress_percentage'] ?>%"></div>
                                </div>
                                <div class="progress-text">
                                    <?= number_format($level_progress['needed_exp'] - $level_progress['progress_exp']) ?> XP to Level <?= $level_progress['current_level'] + 1 ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- User Statistics -->
                <section class="stats-section">
                    <h2>Your Statistics</h2>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">📝</div>
                            <div class="stat-number"><?= $user_stats['review_count'] ?></div>
                            <div class="stat-label">Reviews</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">🎨</div>
                            <div class="stat-number"><?= $user_stats['fanart_count'] ?></div>
                            <div class="stat-label">Fanart</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">💬</div>
                            <div class="stat-number"><?= $user_stats['discussion_count'] ?></div>
                            <div class="stat-label">Discussions</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">⚔️</div>
                            <div class="stat-number"><?= $user_stats['debate_count'] ?></div>
                            <div class="stat-label">Debates</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">👥</div>
                            <div class="stat-number"><?= $user_stats['follower_count'] ?></div>
                            <div class="stat-label">Followers</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">➡️</div>
                            <div class="stat-number"><?= $user_stats['following_count'] ?></div>
                            <div class="stat-label">Following</div>
                        </div>
                    </div>
                </section>

                <!-- Badges Section -->
                <section class="badges-section">
                    <div class="section-header">
                        <h2>Your Badges (<?= count($user_badges) ?>)</h2>
                        <div class="section-actions">
                            <!-- Badge links removed as requested -->
                        </div>
                    </div>
                    
                    <?php if (!empty($user_badges)): ?>
                        <div class="badges-grid">
                            <?php foreach ($user_badges as $badge): ?>
                                <div class="badge-item">
                                    <div class="badge-icon">
                                        <?php 
                                            $icon = $badge['badge_icon'] ?? '🏅';
                                            // Fallback for emoji display issues
                                            if (empty($icon) || $icon === '????' || mb_strlen($icon) > 10) {
                                                $fallback_icons = [
                                                    'First Reviewer' => '📝',
                                                    'Review Enthusiast' => '✍️', 
                                                    'Review Master' => '🏆',
                                                    'First Artist' => '🎨',
                                                    'Art Enthusiast' => '🖼️',
                                                    'Conversation Starter' => '💬',
                                                    'Discussion Leader' => '🗣️',
                                                    'Popular User' => '⭐',
                                                    'Community Star' => '🌟',
                                                    'Active Member' => '🔥',
                                                    'Veteran Member' => '🎖️',
                                                    'Level 5 Achieved' => '🏅',
                                                    'Level 10 Achieved' => '👑'
                                                ];
                                                $badge_name = $badge['badge_name'] ?? 'Achievement';
                                                $icon = $fallback_icons[$badge_name] ?? '🏅';
                                            }
                                            echo $icon;
                                        ?>
                                    </div>
                                    <div class="badge-info">
                                        <h4 class="badge-name"><?= htmlspecialchars($badge['badge_name']) ?></h4>
                                        <p class="badge-description"><?= htmlspecialchars($badge['badge_description']) ?></p>
                                        <div class="badge-meta">
                                            <span class="earned-date">Earned <?= date('M j, Y', strtotime($badge['earned_at'])) ?></span>
                                        </div>
                                        <div class="badge-actions">
                                            <!-- Set as Primary link removed as requested -->
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-badges">
                            <div class="no-badges-icon">🎯</div>
                            <h3>No badges yet!</h3>
                            <p>Start participating in the community to earn your first badge.</p>
                            <div class="quick-actions">
                                <a href="?page=animereview" class="btn btn-primary">Write a Review</a>
                                <a href="?page=fanart" class="btn btn-secondary">Upload Fanart</a>
                                <a href="?page=discussion" class="btn btn-secondary">Join Discussions</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Experience Breakdown -->
                <section class="experience-section">
                    <h2>Experience Breakdown</h2>
                    <?php if (!empty($experience_breakdown)): ?>
                        <div class="experience-breakdown">
                            <?php foreach ($experience_breakdown as $exp): ?>
                                <div class="exp-item">
                                    <div class="exp-action"><?= ucfirst(str_replace('_', ' ', $exp['action_type'])) ?></div>
                                    <div class="exp-details">
                                        <span class="exp-count"><?= $exp['action_count'] ?> times</span>
                                        <span class="exp-points"><?= number_format($exp['total_points']) ?> XP</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-content">No experience earned yet. Start participating to gain XP!</p>
                    <?php endif; ?>
                </section>

                <!-- Recent Activity -->
                <section class="recent-activity">
                    <h2>Recent Experience</h2>
                    <?php if (!empty($recent_experience)): ?>
                        <div class="activity-list">
                            <?php foreach ($recent_experience as $activity): ?>
                                <div class="activity-item">
                                    <div class="activity-info">
                                        <span class="activity-description"><?= htmlspecialchars($activity['description']) ?></span>
                                        <span class="activity-date"><?= date('M j, Y H:i', strtotime($activity['earned_at'])) ?></span>
                                    </div>
                                    <div class="activity-points">+<?= $activity['points_earned'] ?> XP</div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-content">No recent activity.</p>
                    <?php endif; ?>
                </section>
            </main>

            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- How to Earn Badges -->
                <section class="earn-badges-section">
                    <h3>🎯 How to Earn Badges & XP</h3>
                    
                    <div class="earn-info">
                        <h4>📈 Experience Points (XP)</h4>
                        <div class="xp-list">
                            <div class="xp-item">
                                <span class="xp-action">📝 Write a Review</span>
                                <span class="xp-points">+20 XP</span>
                            </div>
                            <div class="xp-item">
                                <span class="xp-action">🎨 Upload Fanart</span>
                                <span class="xp-points">+15 XP</span>
                            </div>
                            <div class="xp-item">
                                <span class="xp-action">💬 Start Discussion</span>
                                <span class="xp-points">+10 XP</span>
                            </div>
                            <div class="xp-item">
                                <span class="xp-action">⚔️ Start Debate</span>
                                <span class="xp-points">+10 XP</span>
                            </div>
                            <div class="xp-item">
                                <span class="xp-action">📊 Create Poll</span>
                                <span class="xp-points">+8 XP</span>
                            </div>
                            <div class="xp-item">
                                <span class="xp-action">💭 Post Comment</span>
                                <span class="xp-points">+5 XP</span>
                            </div>
                            <div class="xp-item">
                                <span class="xp-action">👤 Update Profile</span>
                                <span class="xp-points">+5 XP</span>
                            </div>
                            <div class="xp-item">
                                <span class="xp-action">🗳️ Cast Vote</span>
                                <span class="xp-points">+2 XP</span>
                            </div>
                            <div class="xp-item">
                                <span class="xp-action">🌅 Daily Login</span>
                                <span class="xp-points">+1 XP</span>
                            </div>
                        </div>
                    </div>

                    <div class="badge-info">
                        <h4>🏆 Automatic Badges</h4>
                        <div class="badge-categories">
                            <div class="category">
                                <h5>📝 Review Badges</h5>
                                <ul>
                                    <li>First Reviewer - Write 1 review</li>
                                    <li>Review Enthusiast - Write 10 reviews</li>
                                    <li>Review Master - Write 50 reviews</li>
                                </ul>
                            </div>
                            <div class="category">
                                <h5>🎨 Art Badges</h5>
                                <ul>
                                    <li>First Artist - Upload 1 fanart</li>
                                    <li>Art Enthusiast - Upload 10 fanarts</li>
                                </ul>
                            </div>
                            <div class="category">
                                <h5>💬 Social Badges</h5>
                                <ul>
                                    <li>Conversation Starter - Start 1 discussion</li>
                                    <li>Discussion Leader - Start 20 discussions</li>
                                    <li>Popular User - Gain 10 followers</li>
                                    <li>Community Star - Gain 50 followers</li>
                                </ul>
                            </div>
                            <div class="category">
                                <h5>🎖️ Achievement Badges</h5>
                                <ul>
                                    <li>Active Member - Earn 500 XP</li>
                                    <li>Veteran Member - Earn 2000 XP</li>
                                    <li>Level 5 Achieved - Reach Level 5</li>
                                    <li>Level 10 Achieved - Reach Level 10</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="progression-info">
                        <h4>📊 Level Progression</h4>
                        <p>Your level is calculated automatically based on your total XP. The more you participate in the community, the higher your level becomes!</p>
                        <p><strong>Formula:</strong> Level = √(Total XP ÷ 100) + 1</p>
                    </div>
                </section>

                <!-- Quick Links -->
                <section class="quick-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="?page=myprofile">My Profile</a></li>
                    </ul>
                </section>
            </aside>
        </div>
    </div>

    <script>
        // Smooth progress bar animation
        window.addEventListener('load', function() {
            const progressBar = document.querySelector('.progress-fill');
            if (progressBar) {
                const targetWidth = progressBar.style.width;
                progressBar.style.width = '0%';
                setTimeout(() => {
                    progressBar.style.transition = 'width 1s ease-out';
                    progressBar.style.width = targetWidth;
                }, 100);
            }
        });

        // Badge item hover effects
        document.querySelectorAll('.badge-item').forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>