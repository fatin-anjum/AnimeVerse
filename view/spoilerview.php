<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spoiler Management - AnimeVerse</title>
    <link rel="stylesheet" href="css/spoiler.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Critical CSS fallback in case external CSS doesn't load */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 50%, #D2691E 100%);
            margin: 0; padding: 0; min-height: 100vh;
        }
        .navbar { 
            background: rgba(255, 255, 255, 0.95);
            padding: 1rem 2rem; display: flex; justify-content: space-between;
            position: sticky; top: 0; z-index: 1000;
        }
        .navbar-left span { 
            font-size: 1.8rem; font-weight: 900;
            background: linear-gradient(45deg, #8B4513, #D2691E);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .navbar-right { display: flex; gap: 1.5rem; }
        .navbar-right a { 
            color: #333; text-decoration: none; padding: 0.5rem 1rem;
            border-radius: 8px; transition: all 0.3s ease;
        }
        .navbar-right a:hover, .navbar-right a.active {
            background: linear-gradient(45deg, #8B4513, #D2691E);
            color: white;
        }
    </style>
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
            <a href="?page=discussion"><i class="fas fa-comments"></i> Discussions</a>
            <a href="?page=collectibles"><i class="fas fa-shopping-cart"></i> Marketplace</a>
            <a href="?page=badge"><i class="fas fa-trophy"></i> Badges</a>
            <a href="?page=follow"><i class="fas fa-users"></i> Social</a>
            <a href="?page=spoiler" class="active"><i class="fas fa-eye-slash"></i> Spoiler Management</a>
            <a href="?page=animereview"><i class="fas fa-star"></i> Reviews</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="spoiler-container">
        <!-- Page Header -->
        <header class="page-header">
            <h1><i class="fas fa-eye-slash"></i> Spoiler Management</h1>
            <p>Manage and view spoiler-tagged content across the platform</p>
        </header>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['message']) ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="content-layout">
            <!-- Management Sidebar -->
            <aside class="management-sidebar">
                <!-- Statistics Section -->
                <section class="stats-section">
                    <h2><i class="fas fa-chart-bar"></i> Spoiler Statistics</h2>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number"><?= $spoiler_stats['spoiler_reviews'] ?></div>
                            <div class="stat-label">Spoiler Reviews</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"><?= $spoiler_stats['spoiler_fanart'] ?></div>
                            <div class="stat-label">Spoiler Fanart</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"><?= $spoiler_stats['spoiler_discussions'] ?></div>
                            <div class="stat-label">Spoiler Discussions</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"><?= $spoiler_stats['spoiler_debates'] ?></div>
                            <div class="stat-label">Spoiler Debates</div>
                        </div>
                    </div>
                </section>

                <!-- Quick Actions Section -->
                <section class="quick-actions-section">
                    <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                    <div class="action-buttons">
                        <button class="btn btn-primary" onclick="showMarkSpoilerForm()">
                            <i class="fas fa-plus"></i> Mark Content as Spoiler
                        </button>
                        <button class="btn btn-secondary" onclick="showUpdateWarningForm()">
                            <i class="fas fa-edit"></i> Update Warning
                        </button>
                        <a href="?page=animereview" class="btn btn-outline">
                            <i class="fas fa-star"></i> View Reviews
                        </a>
                        <a href="?page=fanart" class="btn btn-outline">
                            <i class="fas fa-palette"></i> View Fanart
                        </a>
                    </div>
                </section>

                <!-- Mark as Spoiler Form (Hidden by default) -->
                <section class="form-section" id="markSpoilerForm" style="display: none;">
                    <h3><i class="fas fa-exclamation-triangle"></i> Mark Content as Spoiler</h3>
                    <form action="?page=spoiler&action=mark" method="POST" class="spoiler-form">
                        <div class="form-group">
                            <label for="content_type"><i class="fas fa-tags"></i> Content Type:</label>
                            <select id="content_type" name="content_type" required>
                                <option value="">Select content type...</option>
                                <option value="fanart">Fanart</option>
                                <option value="discussion">Discussion</option>
                                <option value="debate">Debate</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="content_id"><i class="fas fa-hashtag"></i> Content ID:</label>
                            <input type="number" id="content_id" name="content_id" required>
                            <small>Enter the ID of the content to mark as spoiler</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="spoiler_warning"><i class="fas fa-exclamation"></i> Spoiler Warning:</label>
                            <input type="text" id="spoiler_warning" name="spoiler_warning" required 
                                   placeholder="e.g., 'Contains ending spoilers for Episode 25'">
                        </div>
                        
                        <input type="hidden" name="redirect_url" value="?page=spoiler">
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Mark as Spoiler
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="hideMarkSpoilerForm()">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                </section>

                <!-- Update Warning Form (Hidden by default) -->
                <section class="form-section" id="updateWarningForm" style="display: none;">
                    <h3><i class="fas fa-edit"></i> Update Spoiler Warning</h3>
                    <form action="?page=spoiler&action=update" method="POST" class="spoiler-form">
                        <div class="form-group">
                            <label for="update_content_type"><i class="fas fa-tags"></i> Content Type:</label>
                            <select id="update_content_type" name="content_type" required>
                                <option value="">Select content type...</option>
                                <option value="fanart">Fanart</option>
                                <option value="discussion">Discussion</option>
                                <option value="debate">Debate</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="update_content_id"><i class="fas fa-hashtag"></i> Content ID:</label>
                            <input type="number" id="update_content_id" name="content_id" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="update_spoiler_warning"><i class="fas fa-exclamation"></i> New Spoiler Warning:</label>
                            <input type="text" id="update_spoiler_warning" name="spoiler_warning" required>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Warning
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="hideUpdateWarningForm()">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                </section>
            </aside>

            <!-- Main Content Area -->
            <main class="main-content">
                <!-- Spoiler Content List -->
                <section class="content-section">
                    <h2><i class="fas fa-list"></i> All Content with IDs (Spoiler Management)</h2>
                    
                    <!-- Get all content for management -->
                    <?php 
                    $all_content = $spoilerModel->getAllContentWithIds();
                    ?>
                    
                    <!-- Content Management Tabs -->
                    <div class="tabs">
                        <div class="tab active" onclick="showTab('fanart')">
                            <i class="fas fa-palette"></i> Fanart (<?= count($all_content['fanart']) ?>)
                        </div>
                        <div class="tab" onclick="showTab('discussions')">
                            <i class="fas fa-comments"></i> Discussions (<?= count($all_content['discussions']) ?>)
                        </div>
                        <div class="tab" onclick="showTab('debates')">
                            <i class="fas fa-users"></i> Debates (<?= count($all_content['debates']) ?>)
                        </div>
                        <div class="tab" onclick="showTab('reviews')">
                            <i class="fas fa-star"></i> Reviews (<?= count($all_content['reviews']) ?>)
                        </div>
                    </div>
            
            <!-- Fanart Tab -->
            <div id="fanart-tab" class="tab-content active content-section">
                <h3>📸 Fanart Content</h3>
                <?php if (!empty($all_content['fanart'])): ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th style="width: 200px;">Title</th>
                                <th style="width: 120px;">User</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 180px;">Warning</th>
                                <th style="width: 80px;">Date</th>
                                <th style="width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_content['fanart'] as $item): ?>
                                <tr>
                                    <td class="id-column"><?= $item['id'] ?></td>
                                    <td class="title-column"><?= htmlspecialchars($item['title']) ?></td>
                                    <td class="user-column"><?= htmlspecialchars($item['username']) ?></td>
                                    <td class="status-column">
                                        <?php if ($item['is_spoiler']): ?>
                                            <span class="spoiler-badge">⚠️ SPOILER</span>
                                        <?php else: ?>
                                            <span class="safe-badge">✅ Safe</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="warning-column"><?= $item['spoiler_warning'] ? htmlspecialchars($item['spoiler_warning']) : '-' ?></td>
                                    <td class="date-column"><?= date('M j', strtotime($item['created_at'])) ?></td>
                                    <td class="actions-column">
                                        <?php if ($item['is_spoiler']): ?>
                                            <a href="?page=spoiler&action=unmark&content_type=fanart&content_id=<?= $item['id'] ?>" 
                                               class="btn btn-success btn-sm">Remove Spoiler</a>
                                        <?php else: ?>
                                            <button class="btn btn-warning btn-sm" 
                                                    onclick="markAsSpoiler('fanart', <?= $item['id'] ?>)">Mark as Spoiler</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="warning">No fanart found.</p>
                <?php endif; ?>
            </div>
            
            <!-- Discussions Tab -->
            <div id="discussions-tab" class="tab-content content-section">
                <h3>💬 Discussion Content</h3>
                <?php if (!empty($all_content['discussions'])): ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th style="width: 200px;">Title</th>
                                <th style="width: 100px;">Genre</th>
                                <th style="width: 120px;">User</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 180px;">Warning</th>
                                <th style="width: 80px;">Date</th>
                                <th style="width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_content['discussions'] as $item): ?>
                                <tr>
                                    <td class="id-column"><?= $item['id'] ?></td>
                                    <td class="title-column"><?= htmlspecialchars($item['title']) ?></td>
                                    <td class="genre-column"><?= htmlspecialchars($item['genre_name'] ?? 'N/A') ?></td>
                                    <td class="user-column"><?= htmlspecialchars($item['username']) ?></td>
                                    <td class="status-column">
                                        <?php if ($item['is_spoiler']): ?>
                                            <span class="spoiler-badge">⚠️ SPOILER</span>
                                        <?php else: ?>
                                            <span class="safe-badge">✅ Safe</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="warning-column"><?= $item['spoiler_warning'] ? htmlspecialchars($item['spoiler_warning']) : '-' ?></td>
                                    <td class="date-column"><?= date('M j', strtotime($item['created_at'])) ?></td>
                                    <td class="actions-column">
                                        <?php if ($item['is_spoiler']): ?>
                                            <a href="?page=spoiler&action=unmark&content_type=discussion&content_id=<?= $item['id'] ?>" 
                                               class="btn btn-success btn-sm">Remove Spoiler</a>
                                        <?php else: ?>
                                            <button class="btn btn-warning btn-sm" 
                                                    onclick="markAsSpoiler('discussion', <?= $item['id'] ?>)">Mark as Spoiler</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="warning">No discussions found.</p>
                <?php endif; ?>
            </div>
            
            <!-- Debates Tab -->
            <div id="debates-tab" class="tab-content content-section">
                <h3>🗣️ Debate Content</h3>
                <?php if (!empty($all_content['debates'])): ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th style="width: 250px;">Title</th>
                                <th style="width: 120px;">User</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 180px;">Warning</th>
                                <th style="width: 80px;">Date</th>
                                <th style="width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_content['debates'] as $item): ?>
                                <tr>
                                    <td class="id-column"><?= $item['id'] ?></td>
                                    <td class="title-column"><?= htmlspecialchars($item['title']) ?></td>
                                    <td class="user-column"><?= htmlspecialchars($item['username']) ?></td>
                                    <td class="status-column">
                                        <?php if ($item['is_spoiler']): ?>
                                            <span class="spoiler-badge">⚠️ SPOILER</span>
                                        <?php else: ?>
                                            <span class="safe-badge">✅ Safe</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="warning-column"><?= $item['spoiler_warning'] ? htmlspecialchars($item['spoiler_warning']) : '-' ?></td>
                                    <td class="date-column"><?= date('M j', strtotime($item['created_at'])) ?></td>
                                    <td class="actions-column">
                                        <?php if ($item['is_spoiler']): ?>
                                            <a href="?page=spoiler&action=unmark&content_type=debate&content_id=<?= $item['id'] ?>" 
                                               class="btn btn-success btn-sm">Remove Spoiler</a>
                                        <?php else: ?>
                                            <button class="btn btn-warning btn-sm" 
                                                    onclick="markAsSpoiler('debate', <?= $item['id'] ?>)">Mark as Spoiler</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="warning">No debates found.</p>
                <?php endif; ?>
            </div>
            
            <!-- Reviews Tab -->
            <div id="reviews-tab" class="tab-content content-section">
                <h3>⭐ Review Content</h3>
                <?php if (!empty($all_content['reviews'])): ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th style="width: 150px;">Review</th>
                                <th style="width: 150px;">Anime</th>
                                <th style="width: 120px;">User</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 180px;">Warning</th>
                                <th style="width: 80px;">Date</th>
                                <th style="width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_content['reviews'] as $item): ?>
                                <tr>
                                    <td class="id-column"><?= $item['id'] ?></td>
                                    <td class="title-column"><?= htmlspecialchars($item['title']) ?></td>
                                    <td class="title-column"><?= htmlspecialchars($item['anime_title']) ?></td>
                                    <td class="user-column"><?= htmlspecialchars($item['username']) ?></td>
                                    <td class="status-column">
                                        <?php if ($item['is_spoiler']): ?>
                                            <span class="spoiler-badge">⚠️ SPOILER</span>
                                        <?php else: ?>
                                            <span class="safe-badge">✅ Safe</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="warning-column"><?= $item['spoiler_warning'] ? htmlspecialchars($item['spoiler_warning']) : '-' ?></td>
                                    <td class="date-column"><?= date('M j', strtotime($item['created_at'])) ?></td>
                                    <td class="actions-column">
                                        <?php if ($item['is_spoiler']): ?>
                                            <a href="?page=spoiler&action=unmark&content_type=review&content_id=<?= $item['id'] ?>" 
                                               class="btn btn-success btn-sm">Remove Spoiler</a>
                                        <?php else: ?>
                                            <button class="btn btn-warning btn-sm" 
                                                    onclick="markAsSpoiler('review', <?= $item['id'] ?>)">Mark as Spoiler</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="warning">No reviews found.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Guidelines -->
        <section class="spoiler-guidelines">
            <h2>Spoiler Guidelines</h2>
            <div class="guidelines-content">
                <h3>When to Use Spoiler Tags:</h3>
                <ul>
                    <li>Plot twists or major story reveals</li>
                    <li>Character deaths or major character developments</li>
                    <li>Ending discussions or finale content</li>
                    <li>Major plot points from recent episodes</li>
                    <li>Future story content from manga/novels</li>
                </ul>
                
                <h3>How to Write Good Spoiler Warnings:</h3>
                <ul>
                    <li>Be specific about what episodes/chapters are spoiled</li>
                    <li>Mention the type of spoiler (ending, character death, etc.)</li>
                    <li>Keep warnings clear but not spoilery themselves</li>
                    <li>Examples: "Episode 25 ending spoilers", "Major character death in Season 2"</li>
                </ul>
            </div>
        </section>
    </div>

    <script>
        function showMarkSpoilerForm() {
            document.getElementById('markSpoilerForm').style.display = 'block';
            document.getElementById('updateWarningForm').style.display = 'none';
        }

        function hideMarkSpoilerForm() {
            document.getElementById('markSpoilerForm').style.display = 'none';
        }

        function showUpdateWarningForm() {
            document.getElementById('updateWarningForm').style.display = 'block';
            document.getElementById('markSpoilerForm').style.display = 'none';
        }

        function hideUpdateWarningForm() {
            document.getElementById('updateWarningForm').style.display = 'none';
        }

        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab');
            tabButtons.forEach(button => button.classList.remove('active'));
            
            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.add('active');
            
            // Add active class to clicked tab button
            event.target.classList.add('active');
        }

        // Quick mark as spoiler function
        function markAsSpoiler(contentType, contentId) {
            const warning = prompt('Enter spoiler warning:', 'Contains spoilers');
            if (warning && warning.trim()) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '?page=spoiler&action=mark';
                
                const typeInput = document.createElement('input');
                typeInput.type = 'hidden';
                typeInput.name = 'content_type';
                typeInput.value = contentType;
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'content_id';
                idInput.value = contentId;
                
                const warningInput = document.createElement('input');
                warningInput.type = 'hidden';
                warningInput.name = 'spoiler_warning';
                warningInput.value = warning.trim();
                
                const redirectInput = document.createElement('input');
                redirectInput.type = 'hidden';
                redirectInput.name = 'redirect_url';
                redirectInput.value = '?page=spoiler';
                
                form.appendChild(typeInput);
                form.appendChild(idInput);
                form.appendChild(warningInput);
                form.appendChild(redirectInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Auto-hide forms when clicking outside
        document.addEventListener('click', function(e) {
            const forms = ['markSpoilerForm', 'updateWarningForm'];
            forms.forEach(formId => {
                const form = document.getElementById(formId);
                if (form) {
                    const isClickInside = form.contains(e.target);
                    const isButton = e.target.matches('.btn') || e.target.closest('.action-buttons');
                    
                    if (!isClickInside && !isButton && form.style.display === 'block') {
                        form.style.display = 'none';
                    }
                }
            });
        });
    </script>
</body>
</html>