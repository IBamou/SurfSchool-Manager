<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coaches - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/coaches.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/toast.css">
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
                <a href="<?= $baseUrl ?>profile">Profile</a>
                <form action="<?= $baseUrl ?>auth/logout" method="POST">
                    <button type="submit" class="nav-btn">Logout</button>
                </form>
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
                            <button type="button" class="btn btn-red-outline btn-sm" onclick="confirmDelete(<?= $coach['id'] ?>, '<?= htmlspecialchars(addslashes($coach['name'])) ?>')">Delete</button>
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

    <script src="<?= $baseUrl ?>app/Views/js/toast.js"></script>
    <script>
        function confirmDelete(id, name) {
            ConfirmModal.delete({
                title: 'Delete Coach',
                message: `Are you sure you want to delete "${name}"? This action cannot be undone.`,
                onConfirm: () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= $baseUrl ?>coaches/delete/' + id;
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
