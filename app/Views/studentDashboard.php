<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/student-dashboard.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>/student/dashboard" class="active">Dashboard</a>
                <a href="<?= $baseUrl ?>/student/lessons">Lessons</a>
                <a href="<?= $baseUrl ?>/student/profile">Profile</a>
                <a href="<?= $baseUrl ?>/auth/logout">Logout</a>
            </nav>
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
            <h2>My Sessions</h2>
            <?php if (!empty($mySessions)): ?>
                <div class="sessions-list">
                    <?php foreach ($mySessions as $session): ?>
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
                    <p>No sessions enrolled yet.</p>
                    <a href="<?= $baseUrl ?>/student/lessons" class="btn btn-primary">Browse Lessons</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>Available for You</h2>
            <p class="section-desc">Based on your <?= htmlspecialchars($user['level'] ?? 'Beginner') ?> level</p>
            <?php if (!empty($recommendedLessons)): ?>
                <div class="lessons-preview">
                    <?php foreach (array_slice($recommendedLessons, 0, 3) as $lesson): ?>
                        <div class="lesson-preview-card">
                            <span class="badge badge-<?= strtolower($lesson['level'] ?? 'beginner') ?>">
                                <?= htmlspecialchars($lesson['level'] ?? '') ?>
                            </span>
                            <h4><?= htmlspecialchars($lesson['title'] ?? '') ?></h4>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="<?= $baseUrl ?>/student/lessons" class="btn btn-secondary">View All Lessons</a>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>
