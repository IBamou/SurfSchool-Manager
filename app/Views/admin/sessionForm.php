<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEditing ? 'Edit' : 'Add' ?> Session - <?= $siteName ?? 'SurfManager' ?></title>
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
                <a href="<?= $baseUrl ?>sessions" class="active">Sessions</a>
                <a href="<?= $baseUrl ?>students">Students</a>
                <a href="<?= $baseUrl ?>coaches">Coaches</a>
                <a href="<?= $baseUrl ?>profile">Profile</a>
                <a href="<?= $baseUrl ?>auth/logout">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container form-page">
        <div class="form-card">
            <h1><?= $isEditing ? 'Edit' : 'Add' ?> Session</h1>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="<?= $baseUrl ?>sessions/<?= $isEditing ? 'edit/' . $session['id'] : 'add' ?>" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="lesson_id">Lesson *</label>
                        <select id="lesson_id" name="lesson_id" required>
                            <?php foreach ($lessons as $lesson): ?>
                                <?php if (isset($_SESSION['lesson_id'])) :?>
                                    <?php if($_SESSION['lesson_id']  == $lesson['id']) : ?>
                                        <option value="<?= $lesson['id'] ?>" selected>
                                            <?= htmlspecialchars($lesson['title']) ?> (<?= $lesson['level'] ?>)
                                        </option>
                                     <?php endif?>
                                <?php else :?>
                                <option value="">Select Lesson</option>
                                <option value="<?= $lesson['id'] ?>">
                                    <?= htmlspecialchars($lesson['title']) ?> (<?= $lesson['level'] ?>)
                                </option>
                                <?php endif;?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="coach_id">Coach *</label>
                        <select id="coach_id" name="coach_id" required>
                            <option value="">Select Coach</option>
                            <?php foreach ($coaches as $coach): ?>
                                <option value="<?= $coach['id'] ?>" <?= (($_SESSION['coach_id'] ?? '') == $coach['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($coach['name']) ?> - <?= htmlspecialchars($coach['speciality'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="datetime">Date & Time *</label>
                        <input type="datetime-local" id="datetime" name="datetime" 
                               value="<?= isset($_SESSION['datetime']) ? date('Y-m-d\TH:i', strtotime($_SESSION['datetime'])) : '' ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="duration">Duration (minutes)</label>
                        <input type="number" id="duration" name="duration" 
                               value="<?= $_SESSION['duration'] ?? 60 ?>" min="15" step="15">
                    </div>

                    <div class="form-group">
                        <label for="location">Location *</label>
                        <input type="text" id="location" name="location" 
                               value="<?= htmlspecialchars($_SESSION['location'] ?? '') ?>"
                               placeholder="e.g., Main Beach - Left Point" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Price ($)</label>
                        <input type="number" id="price" name="price" 
                               value="<?= $_SESSION['price'] ?? 0 ?>" min="0" step="0.01">
                    </div>

                    <div class="form-group">
                        <label for="max_spots">Max Spots</label>
                        <input type="number" id="max_spots" name="max_spots" 
                               value="<?= $_SESSION['max_spots'] ?? 8 ?>" min="1" max="50">
                    </div>

                    <?php if ($isEditing): ?>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="available" <?= (($_SESSION['status'] ?? '') === 'available') ? 'selected' : '' ?>>Available</option>
                            <option value="completed" <?= (($_SESSION['status'] ?? '') === 'completed') ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= (($_SESSION['status'] ?? '') === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="form-group full-width">
                        <label for="requirements">Requirements</label>
                        <textarea id="requirements" name="requirements" 
                                  placeholder="Any requirements for this session..."><?= htmlspecialchars($_SESSION['requirements'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="<?= $baseUrl ?>sessions" class="btn-back">Cancel</a>
                    <button type="submit" class="btn btn-primary" disabled>
                        <?= $isEditing ? 'Update' : 'Create' ?> Session
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
