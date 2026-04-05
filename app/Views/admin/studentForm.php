<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/form.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>dashboard" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>dashboard">Dashboard</a>
                <a href="<?= $baseUrl ?>lessons">Lessons</a>
                <a href="<?= $baseUrl ?>sessions">Sessions</a>
                <a href="<?= $baseUrl ?>students" class="active">Students</a>
                <a href="<?= $baseUrl ?>coaches">Coaches</a>
                <a href="<?= $baseUrl ?>profile">Profile</a>
                <form action="<?= $baseUrl ?>auth/logout" method="POST">
                    <button type="submit" class="nav-btn">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container form-page">
        <div class="form-card form-card-sm">
            <h1>Edit Student Level</h1>

            <div class="student-info">
                <p class="student-info-name">
                    <?= htmlspecialchars($student['name'] ?? 'Unknown') ?>
                </p>
                <p class="student-info-email">
                    <?= htmlspecialchars($student['email'] ?? '-') ?>
                </p>
            </div>

            <form action="<?= $baseUrl ?>students/<?= $student['id'] ?>/edit" method="POST">
                <div class="form-group">
                    <label for="level">Surf Level *</label>
                    <select id="level" name="level" required>
                        <option value="Beginner" <?= (($student['level'] ?? '') === 'Beginner') ? 'selected' : '' ?>>Beginner</option>
                        <option value="Intermediate" <?= (($student['level'] ?? '') === 'Intermediate') ? 'selected' : '' ?>>Intermediate</option>
                        <option value="Advanced" <?= (($student['level'] ?? '') === 'Advanced') ? 'selected' : '' ?>>Advanced</option>
                        <option value="Expert" <?= (($student['level'] ?? '') === 'Expert') ? 'selected' : '' ?>>Expert</option>
                    </select>
                    <small class="form-help">
                        Student's skill level determines which lessons they can access.
                    </small>
                </div>

                <div class="form-actions">
                    <a href="<?= $baseUrl ?>students/<?= $student['id'] ?>" class="btn-back">Cancel</a>
                    <button type="submit" class="btn btn-primary" disabled>Update Level</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        const levelSelect = document.getElementById('level');
        const originalValue = levelSelect.value;

        levelSelect.addEventListener('change', function() {
            submitBtn.disabled = (this.value === originalValue);
        });
    </script>
</body>

</html>
