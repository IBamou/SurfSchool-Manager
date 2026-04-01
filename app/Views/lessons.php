<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surf Lessons - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/lessons.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>home">Home</a>
                <a href="<?= $baseUrl ?>lessons" class="active">Lessons</a>
                <a href="<?= $baseUrl ?>sessions">Sessions</a>
                <a href="<?= $baseUrl ?>students">Students</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Our Surf Lessons</h1>
            <p>Master the art of surfing with our professional instructors</p>
        </div>
    </section>

    <main class="container">
        <!-- Search Section -->
        <div class="search-section">
            <form action="<?= $baseUrl ?>lessons" method="GET" class="search-form">
                <div class="search-input">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search lessons..." 
                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                    >
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <div class="filters-row">
                <select name="level" onchange="this.form.submit()">
                    <option value="">All Levels</option>
                    <option value="Beginner" <?= (isset($_GET['level']) && $_GET['level'] === 'Beginner') ? 'selected' : '' ?>>Beginner</option>
                    <option value="Intermediate" <?= (isset($_GET['level']) && $_GET['level'] === 'Intermediate') ? 'selected' : '' ?>>Intermediate</option>
                    <option value="Advanced" <?= (isset($_GET['level']) && $_GET['level'] === 'Advanced') ? 'selected' : '' ?>>Advanced</option>
                    <option value="Expert" <?= (isset($_GET['level']) && $_GET['level'] === 'Expert') ? 'selected' : '' ?>>Expert</option>
                </select>
            </div>
        </div>

        <!-- Actions Row -->
        <div class="actions-row">
            <div class="results-info">
                <?= count($lessons ?? []) ?> Lessons Available
            </div>
            <a href="<?= $baseUrl ?>/lessons/add" class="btn btn-primary">
                + Add New Lesson
            </a>
        </div>

        <!-- Lessons Grid -->
        <?php if (!empty($lessons)): ?>
            <div class="lessons-grid">
                <?php foreach ($lessons as $lesson): ?>
                    <div class="lesson-card">
                        <div class="lesson-image">
                            🏄
                        </div>
                        <div class="lesson-content">
                            <span class="badge badge-<?= strtolower($lesson['level'] ?? 'beginner') ?>">
                                <?= htmlspecialchars($lesson['level'] ?? 'Beginner') ?>
                            </span>
                            
                            <h2 class="lesson-title"><?= htmlspecialchars($lesson['title']) ?></h2>
                            <p class="lesson-desc"><?= htmlspecialchars($lesson['description'] ?? '') ?></p>

                            <div class="lesson-footer">
                                <a href="<?= $baseUrl ?>lessons/<?= $lesson['id'] ?>" class="btn btn-secondary btn-sm">View Sessions</a>
                                <a href="<?= $baseUrl ?>lessons/edit/<?= $lesson['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">🏄‍♂️</div>
                <h2>No Lessons Found</h2>
                <p>No surf lessons available yet.</p>
                <a href="<?= $baseUrl ?>/lessons/add" class="btn btn-primary">Create First Lesson</a>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>
