<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEditing ? 'Edit' : 'Add' ?> Coach - <?= $siteName ?? 'SurfManager' ?></title>
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
                <a href="<?= $baseUrl ?>students">Students</a>
                <a href="<?= $baseUrl ?>coaches" class="active">Coaches</a>
                <a href="<?= $baseUrl ?>profile">Profile</a>
                <form action="<?= $baseUrl ?>auth/logout" method="POST">
                    <button type="submit" class="nav-btn">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container form-page">
        <div class="form-card">
            <h1><?= $isEditing ? 'Edit' : 'Add New' ?> Coach</h1>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="<?= $baseUrl ?>coaches/<?= $isEditing ? 'edit/' . ($coach['id'] ?? '') : 'add' ?>" method="POST">
                <?php if ($isEditing): ?>
                    <input type="hidden" name="id" value="<?= $coach['id'] ?? '' ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" placeholder="John Smith" value="<?= htmlspecialchars($coach['name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="coach@surf.com" value="<?= htmlspecialchars($coach['email'] ?? '') ?>" required>
                </div>



                <div class="form-group full-width">
                    <label for="speciality">Speciality *</label>
                    <select id="speciality" name="speciality" required>
                        <option value="">Select Speciality</option>
                        <option value="Beginner Friendly" <?= ($coach['speciality'] ?? '') === 'Beginner Friendly' ? 'selected' : '' ?>>Beginner Friendly</option>
                        <option value="Intermediate" <?= ($coach['speciality'] ?? '') === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                        <option value="Advanced Techniques" <?= ($coach['speciality'] ?? '') === 'Advanced Techniques' ? 'selected' : '' ?>>Advanced Techniques</option>
                        <option value="All Levels" <?= ($coach['speciality'] ?? '') === 'All Levels' ? 'selected' : '' ?>>All Levels</option>
                        <option value="Competition Training" <?= ($coach['speciality'] ?? '') === 'Competition Training' ? 'selected' : '' ?>>Competition Training</option>
                        <option value="Kids & Families" <?= ($coach['speciality'] ?? '') === 'Kids & Families' ? 'selected' : '' ?>>Kids & Families</option>
                        <option value="Longboard Style" <?= ($coach['speciality'] ?? '') === 'Longboard Style' ? 'selected' : '' ?>>Longboard Style</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="experience">Years of Experience *</label>
                    <input type="number" id="experience" name="experience" value="<?= $coach['experience'] ?? 1 ?>" min="0" max="50" required>
                </div>
                </div>

                <div class="form-actions">
                    <a href="<?= $baseUrl ?>coaches" class="btn-back">Cancel</a>
                    <button type="submit" class="btn btn-primary"><?= $isEditing ? 'Update' : 'Add' ?> Coach</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
