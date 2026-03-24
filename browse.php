<?php
/**
 * Browse Thesis Page - Redesigned
 * CITAS Smart Archive System
 */

require_once 'db_includes/db_connect.php';
require_login();

// Get all approved theses
$query = "SELECT * FROM thesis WHERE status = 'approved' ORDER BY created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Thesis - CITAS Smart Archive</title>
    <link rel="icon" type="image/png" href="img/CITAS_logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-orange: #E67E22;
            --primary-dark: #D35400;
            --secondary-brown: #8B4513;
            --accent-gold: #F39C12;
            --light-cream: #FFF8F0;
            --text-dark: #2C3E50;
            --text-gray: #7F8C8D;
            --border-light: #ECF0F1;
            --hover-orange: #D65911;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background-color: #FAFAFA;
            color: var(--text-dark);
            line-height: 1.6;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-dark) 100%);
            padding: 0.75rem 2rem;
            box-shadow: 0 2px 8px rgba(230, 126, 34, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 2rem;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            font-size: 1.25rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .logo i {
            font-size: 1.5rem;
        }

        .search-bar {
            flex: 1;
            max-width: 400px;
            display: flex;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            padding: 0.5rem 1rem;
            gap: 0.5rem;
        }

        .search-bar input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            font-size: 0.95rem;
        }

        .search-bar button {
            background: none;
            border: none;
            color: var(--primary-orange);
            cursor: pointer;
            font-size: 1rem;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .nav-link.logout {
            border: 1px solid white;
        }

        /* Main Container */
        .container-main {
            max-width: 1400px;
            margin: 2rem auto;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
            padding: 0 2rem;
        }

        /* Sidebar */
        .sidebar {
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .sidebar-section {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .sidebar-section h3 {
            color: var(--primary-orange);
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 0.75rem;
        }

        .sidebar-menu a {
            color: var(--text-gray);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .sidebar-menu a:hover {
            background: var(--light-cream);
            color: var(--primary-orange);
        }

        .sidebar-menu a.active {
            background: var(--light-cream);
            color: var(--primary-orange);
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
        }

        .page-header h1 {
            color: var(--primary-orange);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: var(--text-gray);
            font-size: 1rem;
        }

        /* Search Section */
        .search-section {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
        }

        .search-section h3 {
            color: var(--primary-orange);
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .search-input-group {
            display: flex;
            gap: 0.5rem;
        }

        .search-input-group input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            font-size: 1rem;
        }

        .search-input-group input:focus {
            outline: none;
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1);
        }

        .search-input-group button {
            background: var(--primary-orange);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .search-input-group button:hover {
            background: var(--hover-orange);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(230, 126, 34, 0.3);
        }

        /* Thesis List */
        .thesis-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .thesis-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
            border-left: 5px solid var(--primary-orange);
            transition: all 0.3s ease;
        }

        .thesis-card:hover {
            box-shadow: 0 4px 16px rgba(230, 126, 34, 0.15);
            transform: translateY(-2px);
        }

        .thesis-title {
            color: var(--primary-orange);
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .thesis-meta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: var(--text-gray);
        }

        .thesis-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .thesis-meta-item i {
            color: var(--primary-orange);
        }

        .thesis-abstract {
            color: var(--text-gray);
            line-height: 1.8;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .thesis-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .thesis-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            font-size: 0.95rem;
        }

        .thesis-btn-primary {
            background: var(--primary-orange);
            color: white;
        }

        .thesis-btn-primary:hover {
            background: var(--hover-orange);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(230, 126, 34, 0.3);
            color: white;
            text-decoration: none;
        }

        .thesis-btn-secondary {
            background: var(--light-cream);
            color: var(--primary-orange);
            border: 1px solid var(--border-light);
        }

        .thesis-btn-secondary:hover {
            background: var(--primary-orange);
            color: white;
            border-color: var(--primary-orange);
        }

        /* Empty State */
        .empty-state {
            background: white;
            padding: 4rem 2rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--primary-orange);
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: var(--primary-orange);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-gray);
            margin-bottom: 1.5rem;
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination-btn {
            background: white;
            border: 1px solid var(--border-light);
            color: var(--text-dark);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .pagination-btn:hover:not(:disabled) {
            background: var(--primary-orange);
            color: white;
            border-color: var(--primary-orange);
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination-btn.active {
            background: var(--primary-orange);
            color: white;
            border-color: var(--primary-orange);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }

            .search-bar {
                max-width: 100%;
            }

            .nav-links {
                gap: 0.75rem;
            }

            .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }

            .container-main {
                grid-template-columns: 1fr;
                padding: 0 1rem;
            }

            .sidebar {
                position: relative;
                top: 0;
            }

            .thesis-card {
                padding: 1.5rem;
            }

            .thesis-title {
                font-size: 1.1rem;
            }

            .thesis-meta {
                flex-direction: column;
                gap: 0.5rem;
            }

            .thesis-actions {
                flex-direction: column;
            }

            .thesis-btn {
                width: 100%;
                justify-content: center;
            }

            .search-input-group {
                flex-direction: column;
            }

            .search-input-group button {
                width: 100%;
            }
        }

        /* Allow text selection */
        .thesis-abstract,
        .thesis-title {
            -webkit-user-select: text;
            -moz-user-select: text;
            -ms-user-select: text;
            user-select: text;
        }
    </style>
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="header-container">
        <div class="logo">
            <i class="fas fa-book-open"></i>
            <span>CITAS Smart Archive</span>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Search theses, authors, topics..." id="headerSearchInput">
            <button type="button" onclick="performHeaderSearch()"><i class="fas fa-search"></i></button>
        </div>
        <nav class="nav-links">
            <a href="index.php" class="nav-link"><i class="fas fa-home"></i> Home</a>
            <div class="notification-center" id="notificationCenter" style="position: relative;">
                <a href="#" class="nav-link" onclick="event.preventDefault(); toggleNotificationPanel()" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge" id="notificationBadge" style="display: none; position: absolute; top: 5px; right: 5px; background: #E74C3C; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold;">0</span>
                </a>
                <div class="notification-dropdown" id="notificationDropdown" style="display: none; position: absolute; top: 100%; right: 0; background: white; border: 1px solid #ECF0F1; border-radius: 8px; width: 350px; max-height: 400px; overflow-y: auto; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1000;">
                    <div style="padding: 1rem; border-bottom: 1px solid #ECF0F1; display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; font-size: 1rem;">Notifications</h4>
                        <button style="background: none; border: none; cursor: pointer; color: #7F8C8D;" onclick="toggleNotificationPanel()">&times;</button>
                    </div>
                    <div id="notificationList" style="max-height: 300px; overflow-y: auto;">
                        <p style="padding: 1rem; text-align: center; color: #7F8C8D;">Loading notifications...</p>
                    </div>
                    <div style="padding: 0.75rem; border-top: 1px solid #ECF0F1; display: flex; gap: 0.5rem;">
                        <button onclick="markAllAsRead()" style="flex: 1; padding: 0.5rem; background: #F8F9F9; border: 1px solid #ECF0F1; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">Mark as Read</button>
                        <button onclick="clearAllNotifications()" style="flex: 1; padding: 0.5rem; background: #F8F9F9; border: 1px solid #ECF0F1; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">Clear All</button>
                    </div>
                </div>
            </div>
            <a href="my_profile.php" class="nav-link">
                <i class="fas fa-user-circle"></i>
                <?php echo htmlspecialchars($_SESSION['full_name']); ?>
            </a>
            <a href="#" class="nav-link logout" onclick="handleLogout(event)"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </div>
</header>

<!-- Main Container -->
<div class="container-main">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-section">
            <h3>Navigation</h3>
            <ul class="sidebar-menu">
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="about.php"><i class="fas fa-info-circle"></i> About</a></li>
                <li><a href="browse.php" class="active"><i class="fas fa-compass"></i> Browse Thesis</a></li>
                <li><a href="favorites.php"><i class="fas fa-heart"></i> Favorites</a></li>
                <?php if (is_admin()): ?>
                <li><a href="admin.php"><i class="fas fa-lock"></i> Admin Panel</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <section class="page-header">
            <h1><i class="fas fa-book me-2"></i>Browse All Theses</h1>
            <p>Explore a complete collection of research papers from the CITAS repository</p>
        </section>

        <!-- Search Section -->
        <div class="search-section">
            <h3><i class="fas fa-search me-2"></i>Search Theses</h3>
            <div class="search-input-group">
                <input type="text" id="mainSearchInput" placeholder="Search by title, author, keywords, or course..." />
                <button onclick="performMainSearch()">Search</button>
            </div>
        </div>

        <!-- Thesis List -->
        <section id="thesisList" class="thesis-list">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($thesis = $result->fetch_assoc()): ?>
                <div class="thesis-card" id="thesis-<?php echo $thesis['id']; ?>">
                    <h3 class="thesis-title"><?php echo htmlspecialchars($thesis['title']); ?></h3>
                    
                    <div class="thesis-meta">
                        <div class="thesis-meta-item">
                            <i class="fas fa-user-circle"></i>
                            <span><?php echo htmlspecialchars($thesis['author']); ?></span>
                        </div>
                        <div class="thesis-meta-item">
                            <i class="fas fa-book"></i>
                            <span><?php echo htmlspecialchars($thesis['course']); ?></span>
                        </div>
                        <div class="thesis-meta-item">
                            <i class="fas fa-calendar"></i>
                            <span><?php echo htmlspecialchars($thesis['year']); ?></span>
                        </div>
                        <div class="thesis-meta-item">
                            <i class="fas fa-eye"></i>
                            <span><?php echo $thesis['views']; ?> views</span>
                        </div>
                    </div>

                    <p class="thesis-abstract">
                        <?php echo htmlspecialchars(substr($thesis['abstract'], 0, 300)) . (strlen($thesis['abstract']) > 300 ? '...' : ''); ?>
                    </p>

                    <div class="thesis-actions">
                        <a href="view_thesis.php?id=<?php echo $thesis['id']; ?>" class="thesis-btn thesis-btn-primary">
                            <i class="fas fa-eye"></i> View Full Details
                        </a>
                        <button class="thesis-btn thesis-btn-secondary" onclick="addToFavorites(<?php echo $thesis['id']; ?>, '<?php echo htmlspecialchars($thesis['title'], ENT_QUOTES); ?>')">
                            <i class="fas fa-bookmark"></i> Add to Favorites
                        </button>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No Theses Found</h3>
                    <p>There are currently no approved theses in the repository.</p>
                    <a href="index.php" class="thesis-btn thesis-btn-primary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            <?php endif; ?>
        </section>

        <!-- Pagination -->
        <div class="pagination-container">
            <button class="pagination-btn" disabled><i class="fas fa-chevron-left"></i> Previous</button>
            <button class="pagination-btn active">1</button>
            <button class="pagination-btn">2</button>
            <button class="pagination-btn">3</button>
            <button class="pagination-btn"><i class="fas fa-chevron-right"></i> Next</button>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
<script>
// Search Functions
function performHeaderSearch() {
    const searchTerm = document.getElementById('headerSearchInput').value;
    if (searchTerm.trim()) {
        filterTheses(searchTerm);
    }
}

function performMainSearch() {
    const searchTerm = document.getElementById('mainSearchInput').value;
    if (searchTerm.trim()) {
        filterTheses(searchTerm);
    }
}

function filterTheses(searchTerm) {
    const searchLower = searchTerm.toLowerCase();
    const thesisCards = document.querySelectorAll('.thesis-card');
    let visibleCount = 0;

    thesisCards.forEach(card => {
        const text = card.textContent.toLowerCase();
        
        if (text.includes(searchLower)) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Show empty state if no results
    if (visibleCount === 0) {
        const list = document.getElementById('thesisList');
        if (!document.querySelector('.no-results-message')) {
            const noResults = document.createElement('div');
            noResults.className = 'empty-state no-results-message';
            noResults.innerHTML = `
                <i class="fas fa-search"></i>
                <h3>No Results Found</h3>
                <p>No theses match your search for "${searchTerm}"</p>
                <button class="thesis-btn thesis-btn-primary" onclick="clearSearch()">
                    <i class="fas fa-redo"></i> Clear Search
                </button>
            `;
            list.appendChild(noResults);
        }
    } else {
        // Remove no results message if it exists
        const noResults = document.querySelector('.no-results-message');
        if (noResults) {
            noResults.remove();
        }
    }
}

function clearSearch() {
    document.getElementById('mainSearchInput').value = '';
    document.getElementById('headerSearchInput').value = '';
    
    const thesisCards = document.querySelectorAll('.thesis-card');
    thesisCards.forEach(card => {
        card.style.display = '';
    });

    const noResults = document.querySelector('.no-results-message');
    if (noResults) {
        noResults.remove();
    }
}

// Add to Favorites
function addToFavorites(thesisId, thesisTitle) {
    fetch('client_includes/add_to_favorites.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'thesis_id=' + thesisId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`"${thesisTitle}" added to favorites!`);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => alert('Error adding to favorites'));
}

// Allow Enter key to submit search
document.getElementById('headerSearchInput')?.addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        performHeaderSearch();
    }
});

document.getElementById('mainSearchInput')?.addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        performMainSearch();
    }
});

// ========== NOTIFICATION SYSTEM ==========

// Toggle notification panel
function toggleNotificationPanel() {
    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown && dropdown.style.display === 'none') {
        dropdown.style.display = 'block';
        loadNotifications();
    } else if (dropdown) {
        dropdown.style.display = 'none';
    }
}

// Load and display notifications
function loadNotifications() {
    fetch('client_includes/get_notifications.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationDisplay(data);
            }
        })
        .catch(error => console.log('Error loading notifications:', error));
}

// Update notification display
function updateNotificationDisplay(data) {
    const badge = document.getElementById('notificationBadge');
    const list = document.getElementById('notificationList');
    
    if (!badge || !list) return;
    
    // Update badge
    if (data.unread_count > 0) {
        badge.textContent = data.unread_count;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
    
    // Update notification list
    if (data.notifications.length > 0) {
        let html = '';
        data.notifications.forEach(notif => {
            const readClass = notif.is_read ? '' : ' style="background: #FFF8F0; border-left: 3px solid #E67E22;"';
            html += `
                <div class="notification-item"${readClass} style="padding: 1rem; border-bottom: 1px solid #ECF0F1; cursor: pointer;" onclick="markNotificationRead(${notif.id})">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #2C3E50; font-size: 0.95rem;">${escapeHtml(notif.title)}</div>
                            <div style="color: #7F8C8D; font-size: 0.85rem; margin-top: 0.25rem;">${escapeHtml(notif.message)}</div>
                            <div style="color: #95A5A6; font-size: 0.75rem; margin-top: 0.5rem;">${notif.time_ago}</div>
                        </div>
                        ${notif.is_read ? '' : '<div style="width: 8px; height: 8px; background: #E67E22; border-radius: 50%; margin-left: 0.5rem; flex-shrink: 0; margin-top: 0.5rem;"></div>'}
                    </div>
                </div>
            `;
        });
        list.innerHTML = html;
    } else {
        list.innerHTML = '<p style="padding: 1rem; text-align: center; color: #7F8C8D;">No notifications yet</p>';
    }
}

// Mark single notification as read
function markNotificationRead(notificationId) {
    fetch('client_includes/mark_notifications_read.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'notification_ids[]=' + notificationId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotifications();
        }
    })
    .catch(error => console.log('Error marking notification as read:', error));
}

// Mark all notifications as read
function markAllAsRead() {
    fetch('client_includes/mark_notifications_read.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: ''
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotifications();
        }
    })
    .catch(error => console.log('Error marking all as read:', error));
}

// Clear all notifications
function clearAllNotifications() {
    if (!confirm('Are you sure you want to clear all notifications?')) return;
    
    fetch('client_includes/clear_all_notifications.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: ''
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotifications();
        }
    })
    .catch(error => console.log('Error clearing notifications:', error));
}

// Helper function to escape HTML
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Auto-refresh notifications every 10 seconds
setInterval(() => {
    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown && dropdown.style.display !== 'none') {
        loadNotifications();
    }
}, 10000);

// Initial load when page loads
document.addEventListener('DOMContentLoaded', () => {
    loadNotifications();
});
</script>

</body>
</html>
<?php $conn->close(); ?>