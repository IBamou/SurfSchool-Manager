<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coaches - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/coaches.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>dashboard" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>dashboard">Dashboard</a>
                <a href="<?= $baseUrl ?>lessons">Lessons</a>
                <a href="<?= $baseUrl ?>sessions">Sessions</a>
                <a href="<?= $baseUrl ?>students">Students</a>
                <a href="<?= $baseUrl ?>coaches" class="active">Coaches</a>
                <a href="<?= $baseUrl ?>auth/logout">Logout</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Coaches</h1>
            <p>Manage your surf school instructors</p>
        </div>
    </section>

    <main class="container">
        <div class="page-header">
            <div class="results-info">
                <strong><?= $totalCoaches ?? 0 ?></strong> coaches
            </div>
            <a href="<?= $baseUrl ?>coaches/add" class="btn btn-primary">+ Add Coach</a>
        </div>

        <?php if (!empty($coaches)): ?>
            <div class="coaches-grid">
                <?php foreach ($coaches as $coach): ?>
                    <div class="coach-card">
                        <div class="coach-avatar">🏄</div>
                        <div class="coach-info">
                            <h3><?= htmlspecialchars($coach['name']) ?></h3>
                            <p class="coach-speciality"><?= htmlspecialchars($coach['speciality'] ?? 'General') ?></p>
                            <p class="coach-experience"><?= htmlspecialchars($coach['experience'] ?? '0') ?> years experience</p>
                            <p class="coach-contact"><?= htmlspecialchars($coach['email'] ?? '') ?></p>
                        </div>
                        <div class="coach-actions">
                            <a href="<?= $baseUrl ?>coaches/edit/<?= $coach['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="<?= $baseUrl ?>coaches/delete/<?= $coach['id'] ?>" method="POST" onsubmit="return confirm('Delete this coach?')">
                                <button type="submit" class="btn btn-red-outline btn-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">🏄</div>
                <h2>No Coaches Yet</h2>
                <p>Add your first coach to get started.</p>
                <a href="<?= $baseUrl ?>coaches/add" class="btn btn-primary">+ Add Coach</a>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>
