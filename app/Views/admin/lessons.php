<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surf Lessons - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/lessons.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/toast.css">
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
                    <button type="submit">Logout</button>
                </form>
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
            <form id="searchForm" action="<?= $baseUrl ?>lessons" method="GET" class="search-form">
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
                            placeholder="Search lessons..." 
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
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="<?= $baseUrl ?>lessons" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>

        <!-- Actions Row -->
        <div class="actions-row">
            <div class="results-info">
                <?= count($lessons ?? []) ?> Lessons Available
            </div>
            <a href="<?= $baseUrl ?>lessons/add" class="btn btn-primary">
                + Add New Lesson
            </a>
        </div>

        <!-- Lessons Grid -->
        <?php if (!empty($lessons)): ?>
            <div class="lessons-grid">
                <?php foreach ($lessons as $lesson): ?>
                    <div class="lesson-card">
                    <div class="lesson-content">
                            <span class="badge badge-<?= strtolower($lesson['level'] ?? 'beginner') ?>">
                                <?= htmlspecialchars($lesson['level'] ?? 'Beginner') ?>
                            </span>
                            
                            <h2 class="lesson-title"><?= htmlspecialchars($lesson['title']) ?></h2>
                            <p class="lesson-desc"><?= htmlspecialchars($lesson['description'] ?? '') ?></p>

                            <div class="lesson-footer">
                                <a href="<?= $baseUrl ?>lessons/<?= $lesson['id'] ?>" class="btn btn-secondary btn-sm">View Sessions</a>
                                <a href="<?= $baseUrl ?>lessons/edit/<?= $lesson['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                                <button type="button" class="btn btn-red-outline btn-sm" onclick="confirmDelete(<?= $lesson['id'] ?>, '<?= htmlspecialchars(addslashes($lesson['title'])) ?>')">Delete</button>
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
                <a href="<?= $baseUrl ?>lessons/add" class="btn btn-primary">Create First Lesson</a>
            </div>
        <?php endif; ?>
    </main>

    <script src="<?= $baseUrl ?>app/Views/js/toast.js"></script>
    <script>
        function confirmDelete(id, title) {
            ConfirmModal.delete({
                title: 'Delete Lesson',
                message: `Are you sure you want to delete "${title}" and all its sessions? This action cannot be undone.`,
                onConfirm: () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= $baseUrl ?>lessons/delete/' + id;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

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
