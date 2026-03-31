<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Lessons - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/student-lessons.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>/student/dashboard">Dashboard</a>
                <a href="<?= $baseUrl ?>/student/lessons" class="active">Lessons</a>
                <a href="<?= $baseUrl ?>/student/profile">Profile</a>
                <a href="<?= $baseUrl ?>/auth/logout">Logout</a>
            </nav>
        </div>
    </header>

    <section class="hero hero-sm">
        <div class="container">
            <h1>Available Lessons</h1>
            <p>Browse and book surf lessons that match your level</p>
        </div>
    </section>

    <main class="container">
        <div class="lessons-grid">
            <?php foreach ($lessons as $lesson): ?>
                <div class="lesson-card">
                    <div class="lesson-image">🏄</div>
                    <div class="lesson-content">
                        <div class="lesson-badges">
                            <span class="badge badge-<?= strtolower($lesson['level'] ?? 'beginner') ?>">
                                <?= htmlspecialchars($lesson['level'] ?? 'Beginner') ?>
                            </span>
                        </div>
                        <h3 class="lesson-title"><?= htmlspecialchars($lesson['title'] ?? '') ?></h3>
                        <p class="lesson-desc"><?= htmlspecialchars($lesson['description'] ?? '') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($lessons)): ?>
            <div class="empty-state">
                <div class="empty-icon">🏄</div>
                <h2>No Lessons Available</h2>
                <p>Check back later for new lessons.</p>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>
