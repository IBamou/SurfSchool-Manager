<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($lesson['title']) ?> - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/lesson.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/form.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>/home">Home</a>
                <a href="<?= $baseUrl ?>/lessons" class="active">Lessons</a>
                <a href="<?= $baseUrl ?>/sessions">Sessions</a>
                <a href="<?= $baseUrl ?>/students">Students</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <!-- Lesson Info -->
        <div class="lesson-detail-card">
            <div class="lesson-detail-header">
                <span class="badge badge-<?= strtolower($lesson['level'] ?? 'beginner') ?>">
                    <?= htmlspecialchars($lesson['level'] ?? 'Beginner') ?>
                </span>
                <h1><?= htmlspecialchars($lesson['title']) ?></h1>
                <p class="lesson-full-desc"><?= htmlspecialchars($lesson['description'] ?? '') ?></p>
            </div>
        </div>

        <!-- Sessions for this Lesson -->
        <div class="section-header">
            <h2>Available Sessions</h2>
            <a href="<?= $baseUrl ?>/sessions/add?lesson_id=<?= $lesson['id'] ?>" class="btn btn-primary btn-sm">+ Add Session</a>
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
                            <a href="<?= $baseUrl ?>/sessions/<?= $session['id'] ?>" class="btn btn-secondary btn-sm">View</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📅</div>
                <h2>No Sessions Yet</h2>
                <p>This lesson doesn't have any scheduled sessions.</p>
                <a href="<?= $baseUrl ?>/sessions/add?lesson_id=<?= $lesson['id'] ?>" class="btn btn-primary">Schedule First Session</a>
            </div>
        <?php endif; ?>

        <!-- Edit/Delete Actions -->
        <div class="admin-actions">
            <a href="<?= $baseUrl ?>/lessons/edit/<?= $lesson['id'] ?>" class="btn btn-secondary">Edit Lesson</a>
            <form action="<?= $baseUrl ?>/lessons/delete/<?= $lesson['id'] ?>" method="POST" class="inline-form" onsubmit="return confirm('Delete this lesson and all its sessions?')">
                <button type="submit" class="btn btn-danger">Delete Lesson</button>
            </form>
        </div>
    </main>

    <style>
        .lesson-detail-card {
            background: var(--white);
            border-radius: 24px;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 10px 40px var(--shadow);
        }
        .lesson-detail-header h1 {
            font-size: 2rem;
            margin: 1rem 0;
            color: var(--text-dark);
        }
        .lesson-full-desc {
            color: var(--text-gray);
            font-size: 1.1rem;
            line-height: 1.8;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 2rem 0 1rem;
        }
        .section-header h2 {
            font-size: 1.5rem;
            color: var(--text-dark);
        }
        .sessions-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .session-item {
            background: var(--white);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        .session-item:hover {
            border-color: var(--ocean-light);
            box-shadow: 0 5px 20px var(--shadow);
        }
        .session-info {
            flex: 1;
        }
        .session-datetime {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        .session-meta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            color: var(--text-gray);
            font-size: 0.9rem;
        }
        .session-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ocean-blue);
            margin: 0 1rem;
        }
        .session-actions {
            display: flex;
            gap: 0.5rem;
        }
        .admin-actions {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
        }
        .btn-danger {
            background: #ef4444;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        @media (max-width: 768px) {
            .session-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .session-price {
                margin: 0;
            }
        }
    </style>
</body>

</html>
