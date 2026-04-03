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
                <a href="<?= $baseUrl ?>auth/logout">Logout</a>
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

                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" placeholder="John Smith" value="<?= htmlspecialchars($coach['name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="coach@surf.com" value="<?= htmlspecialchars($coach['email'] ?? '') ?>" required>
                </div>



                <div class="form-group">
                    <label for="speciality">Speciality *</label>
                    <select id="speciality" name="speciality" required>
                        <option value="">Select Speciality</option>
                        <option value="Beginner" <?= ($coach['speciality'] ?? '') === 'Beginner' ? 'selected' : '' ?>>Beginner Lessons</option>
                        <option value="Intermediate" <?= ($coach['speciality'] ?? '') === 'Intermediate' ? 'selected' : '' ?>>Intermediate Lessons</option>
                        <option value="Advanced" <?= ($coach['speciality'] ?? '') === 'Advanced' ? 'selected' : '' ?>>Advanced Lessons</option>
                        <option value="Competition" <?= ($coach['speciality'] ?? '') === 'Competition' ? 'selected' : '' ?>>Competition Training</option>
                        <option value="Kids" <?= ($coach['speciality'] ?? '') === 'Kids' ? 'selected' : '' ?>>Kids Special</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="experience">Years of Experience *</label>
                    <input type="number" id="experience" name="experience" value="<?= $coach['experience'] ?? 1 ?>" min="0" max="50" required>
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
