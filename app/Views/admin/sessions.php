<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surf Sessions - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/sessions.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/toast.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>dashboard" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>dashboard">Dashboard</a>
                <a href="<?= $baseUrl ?>lessons">Lessons</a>
                <a href="<?= $baseUrl ?>sessions" class="active">Sessions</a>
                <a href="<?= $baseUrl ?>students">Students</a>
                <a href="<?= $baseUrl ?>coaches">Coaches</a>
                <a href="<?= $baseUrl ?>profile">Profile</a>
                <form action="<?= $baseUrl ?>auth/logout" method="POST">
                    <button type="submit">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Surf Sessions</h1>
            <p>Book your spot for an upcoming surf session</p>
        </div>
    </section>

    <main class="container">
        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-icon">🏄</div>
                <div class="stat-number"><?= $totalSessions ?? 0 ?></div>
                <div class="stat-label">Total Sessions</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">✅</div>
                <div class="stat-number"><?= $availableSessions ?? 0 ?></div>
                <div class="stat-label">Available</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">🎯</div>
                <div class="stat-number"><?= $completedSessions ?? 0 ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <form id="searchForm" action="<?= $baseUrl ?>sessions" method="GET" class="search-form">
                <div class="search-input-wrapper">
                    <label class="search-label">Search</label>
                    <div class="search-input">
                        <svg class="search-input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Search sessions, coaches, locations..." 
                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                        >
                    </div>
                </div>
                <div class="filters-wrapper">
                    <div class="filter-group">
                        <label for="levelFilter">Level</label>
                        <select name="level" id="levelFilter">
                            <option value="">All Levels</option>
                            <option value="Beginner" <?= (isset($_GET['level']) && $_GET['level'] === 'Beginner') ? 'selected' : '' ?>>Beginner</option>
                            <option value="Intermediate" <?= (isset($_GET['level']) && $_GET['level'] === 'Intermediate') ? 'selected' : '' ?>>Intermediate</option>
                            <option value="Advanced" <?= (isset($_GET['level']) && $_GET['level'] === 'Advanced') ? 'selected' : '' ?>>Advanced</option>
                            <option value="Expert" <?= (isset($_GET['level']) && $_GET['level'] === 'Expert') ? 'selected' : '' ?>>Expert</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="statusFilter">Status</label>
                        <select name="status" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="available" <?= (isset($_GET['status']) && $_GET['status'] === 'available') ? 'selected' : '' ?>>Available</option>
                            <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] === 'completed') ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= (isset($_GET['status']) && $_GET['status'] === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="coachFilter">Coach</label>
                        <select name="coach" id="coachFilter">
                            <option value="">All Coaches</option>
                            <?php foreach ($coaches ?? [] as $coach): ?>
                                <option value="<?= $coach['id'] ?>" <?= (isset($_GET['coach']) && $_GET['coach'] == $coach['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($coach['name']) ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="none" <?= (isset($_GET['coach']) && $_GET['coach'] === 'none') ? 'selected' : '' ?>>No Coach</option>
                        </select>
                    </div>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="<?= $baseUrl ?>sessions" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>

        <!-- Actions Row -->
        <div class="actions-row">
            <div class="results-info">
                Showing <strong><?= count($sessions ?? []) ?></strong> of <?= $totalSessions ?? 0 ?> sessions
            </div>
            <a href="<?= $baseUrl ?>sessions/add" class="btn btn-primary">
                + Add New Session
            </a>
        </div>

        <!-- Sessions Grid -->
        <?php if (!empty($sessions)): ?>
            <div class="lessons-grid">
                <?php foreach ($sessions as $session): ?>
                    <div class="lesson-card">
                    <div class="lesson-content">
                        <div class="lesson-badges">
                            <span class="badge badge-<?= strtolower($session['lesson_level'] ?? 'beginner') ?>">
                                <?= htmlspecialchars($session['lesson_level'] ?? 'Beginner') ?>
                            </span>
                            <?php
                                $statusClass = strtolower($session['status'] ?? 'available');
                                $statusText = ucfirst($session['status'] ?? 'Available');
                                if ($session['status'] === 'pending_coach') {
                                    $statusClass = 'pending-coach';
                                    $statusText = 'Pending Coach';
                                }
                            ?>
                            <span class="badge badge-<?= $statusClass ?>">
                                <?= htmlspecialchars($statusText) ?>
                            </span>
                        </div>
                            
                            <h2 class="lesson-title"><?= htmlspecialchars($session['lesson_title'] ?? 'Session') ?></h2>
                            
                        <div class="lesson-meta">
                            <div class="meta-item">📅 <?= date('M d, Y H:i', strtotime($session['datetime'])) ?></div>
                            <div class="meta-item">👤 <?= htmlspecialchars($session['coach_name'] ?? '<span style="color: #d97706; font-weight: bold;">No Coach Assigned</span>') ?></div>
                            <div class="meta-item">📍 <?= htmlspecialchars($session['location'] ?? 'TBD') ?></div>
                            <div class="meta-item">⏱ <?= $session['duration'] ?? 60 ?>min</div>
                            <div class="meta-item">👥 <?= $session['spots_available'] ?? '?' ?>/<?= $session['max_spots'] ?? '?' ?> spots</div>
                        </div>

                        <div class="lesson-footer">
                            <div class="lesson-price">
                                $<?= number_format($session['price'] ?? 0, 2) ?>
                                <small>/person</small>
                            </div>
                            <div class="lesson-actions">
                                <a href="<?= $baseUrl ?>sessions/<?= $session['id'] ?>" class="btn btn-secondary btn-sm">View</a>
                                <?php if (($session['status'] ?? '') === 'available' && $session['spots_available'] > 0): ?>
                                    <a href="<?= $baseUrl ?>sessions/book/<?= $session['id'] ?>" class="btn btn-primary btn-sm">Book</a>
                                <?php endif; ?>
                                <?php if (($session['status'] ?? '') === 'pending_coach'): ?>
                                    <a href="<?= $baseUrl ?>sessions/edit/<?= $session['id'] ?>" class="btn btn-primary btn-sm">Assign Coach</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📅</div>
                <h2>No Sessions Found</h2>
                <p>No surf sessions match your search criteria.</p>
                <a href="<?= $baseUrl ?>sessions/add" class="btn btn-primary">Create First Session</a>
            </div>
        <?php endif; ?>
    </main>

    <script src="<?= $baseUrl ?>app/Views/js/toast.js"></script>
    <script>
        // Check for success/error messages in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success')) {
            toast.success(decodeURIComponent(urlParams.get('success')));
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        if (urlParams.get('error')) {
            toast.error(decodeURIComponent(urlParams.get('error')));
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</body>

</html>
