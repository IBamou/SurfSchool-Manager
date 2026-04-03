<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/student-dashboard.css">
</head>

<body>
<header>
    <div class="container header-content">
        <a href="<?= $baseUrl ?>dashboard" class="logo">Surf<span>Manager</span></a>
        <div class="nav-wrapper">
            <nav>
                <a href="<?= $baseUrl ?>dashboard" class="active">Dashboard</a>
                <a href="<?= $baseUrl ?>sessions">Sessions</a>
                <a href="<?= $baseUrl ?>profile">Profile</a>
                <a href="<?= $baseUrl ?>auth/logout">Logout</a>
            </nav>
        </div>
    </div>
</header>

    <section class="hero hero-sm">
        <div class="container">
            <h1>Welcome, <?= htmlspecialchars($user['name'] ?? 'Student') ?>!</h1>
            <p>Your surf journey continues here</p>
        </div>
    </section>

    <main class="container">
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-icon">📚</div>
                <div class="stat-number"><?= $enrolledSessions ?? 0 ?></div>
                <div class="stat-label">Enrolled Sessions</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">✅</div>
                <div class="stat-number"><?= $completedSessions ?? 0 ?></div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">💰</div>
                <div class="stat-number">$<?= number_format($totalSpent ?? 0, 0) ?></div>
                <div class="stat-label">Total Spent</div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">
                <h2>My Sessions</h2>
                <a href="<?= $baseUrl ?>student/sessions" class="btn btn-sm btn-outline">View All</a>
            </div>
            <?php if (!empty($mySessions)): ?>
                <div class="sessions-list">
                    <?php foreach (array_slice($mySessions, 0, 3) as $session): ?>
                        <div class="session-card">
                            <div class="session-info">
                                <h3><?= htmlspecialchars($session['lesson_title'] ?? '') ?></h3>
                                <p class="session-meta">
                                    📅 <?= date('M d, Y - H:i', strtotime($session['datetime'])) ?>
                                    📍 <?= htmlspecialchars($session['location'] ?? '') ?>
                                </p>
                            </div>
                            <span class="badge badge-<?= $session['payment_status'] ?? 'pending' ?>">
                                <?= ucfirst($session['payment_status'] ?? 'Pending') ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>No sessions enrolled yet. Browse available sessions to get started!</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>
