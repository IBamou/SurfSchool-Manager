<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($lesson['title']) ?> - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/lesson.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>dashboard" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>dashboard">Dashboard</a>
                <a href="<?= $baseUrl ?>lessons" class="active">Lessons</a>
                <a href="<?= $baseUrl ?>sessions">Sessions</a>
                <a href="<?= $baseUrl ?>students">Students</a>
                <a href="<?= $baseUrl ?>coaches">Coaches</a>
                <a href="<?= $baseUrl ?>profile">Profile</a>
                <form action="<?= $baseUrl ?>auth/logout" method="POST">
                    <button type="submit" class="nav-btn">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container">
        <!-- Lesson Info -->
        <div class="lesson-detail-card">
            <div class="lesson-header-row">
                <div class="lesson-header-left">
                    <span class="badge badge-<?= strtolower($lesson['level'] ?? 'beginner') ?>">
                        <?= htmlspecialchars($lesson['level'] ?? 'Beginner') ?>
                    </span>
                    <h1><?= htmlspecialchars($lesson['title']) ?></h1>
                </div>
            </div>
            <p class="lesson-full-desc"><?= htmlspecialchars($lesson['description'] ?? '') ?></p>
        </div>

        <!-- Sessions for this Lesson -->
        <div class="section-header">
            <h2 class="section-title">Available Sessions</h2>
            <div class="section-buttons">
                <a href="<?= $baseUrl ?>lessons/edit/<?= $lesson['id'] ?>" class="btn btn-secondary">Edit</a>
                <form action="<?= $baseUrl ?>lessons/delete/<?= $lesson['id'] ?>" method="POST" style="display:inline;">
                    <button type="submit" class="btn btn-red-outline" onclick="return confirm('Delete this lesson and all its sessions?')">Delete</button>
                </form>
                <a href="<?= $baseUrl ?>sessions/add?lesson_id=<?= $lesson['id'] ?>" class="btn btn-primary">+ Add Session</a>
            </div>
        </div>

        <?php if (!empty($sessions)): ?>
            <div class="sessions-list">
                <?php foreach ($sessions as $session): ?>
                    <div class="session-item">
                        <div class="session-info">
                            <div class="session-datetime">
                                📅 <?= date('M d, Y', strtotime($session['datetime'])) ?> at <?= date('H:i', strtotime($session['datetime'])) ?>
                            </div>
                            <div class="session-meta">
                                <span>👤 <?= htmlspecialchars($session['coach_name'] ?? 'TBD') ?></span>
                                <span>📍 <?= htmlspecialchars($session['location'] ?? 'TBD') ?></span>
                                <span>⏱ <?= $session['duration'] ?? 60 ?>min</span>
                                <span>👥 <?= $session['spots_available'] ?? '?' ?>/<?= $session['max_spots'] ?? '?' ?> spots</span>
                            </div>
                        </div>
                        <div class="session-price">
                            $<?= number_format($session['price'] ?? 0, 2) ?>
                        </div>
                        <div class="session-actions">
                            <a href="<?= $baseUrl ?>sessions/<?= $session['id'] ?>" class="btn btn-secondary btn-sm">View</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">🏄</div>
                <h2>No Sessions Yet</h2>
                <p>This lesson doesn't have any scheduled sessions.</p>
                <a href="<?= $baseUrl ?>sessions/add?lesson_id=<?= $lesson['id'] ?>" class="btn btn-primary">Schedule First Session</a>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>
