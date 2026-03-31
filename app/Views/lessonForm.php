<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEditing ? 'Edit' : 'Add' ?> Lesson - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/surf-theme.css">
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

    <main class="container form-page">
        <div class="form-card">
            <h1><?= $isEditing ? 'Edit' : 'Add' ?> Lesson</h1>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="<?= $baseUrl ?>/lessons/<?= $isEditing ? $lesson['id'] . '/edit' : 'add' ?>" method="POST">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" 
                           value="<?= htmlspecialchars($lesson['title'] ?? '') ?>"
                           placeholder="e.g., Beginner Surf Basics" required>
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" 
                              placeholder="Describe what students will learn in this lesson..."
                              required><?= htmlspecialchars($lesson['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="level">Level *</label>
                    <select id="level" name="level" required>
                        <option value="">Select Level</option>
                        <option value="Beginner" <?= (($lesson['level'] ?? '') === 'Beginner') ? 'selected' : '' ?>>Beginner</option>
                        <option value="Intermediate" <?= (($lesson['level'] ?? '') === 'Intermediate') ? 'selected' : '' ?>>Intermediate</option>
                        <option value="Advanced" <?= (($lesson['level'] ?? '') === 'Advanced') ? 'selected' : '' ?>>Advanced</option>
                        <option value="Expert" <?= (($lesson['level'] ?? '') === 'Expert') ? 'selected' : '' ?>>Expert</option>
                    </select>
                </div>

                <div class="form-actions">
                    <a href="<?= $baseUrl ?>/lessons" class="btn-back">Cancel</a>
                    <button type="submit" class="btn btn-primary" disabled>
                        <?= $isEditing ? 'Update' : 'Create' ?> Lesson
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalValues = {};

        form.querySelectorAll('input, textarea, select').forEach(field => {
            originalValues[field.name] = field.value;
            field.addEventListener('input', checkChanges);
            field.addEventListener('change', checkChanges);
        });

        function checkChanges() {
            let hasChanges = false;
            form.querySelectorAll('input, textarea, select').forEach(field => {
                if (field.value !== originalValues[field.name]) {
                    hasChanges = true;
                }
            });
            submitBtn.disabled = !hasChanges;
        }
    </script>
</body>

</html>
