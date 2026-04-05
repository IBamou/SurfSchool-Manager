<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Session - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/sessionBook.css">
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
                <form action="<?= $baseUrl ?>auth/logout" method="POST">
                    <button type="submit" class="btn btn-primary">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container form-page">
        <div class="form-card">
            <h1>Book This Session</h1>

            <div class="session-summary">
                <p><strong><?= htmlspecialchars($session['lesson_title']) ?></strong></p>
                <p>📅 <?= date('M d, Y at H:i', strtotime($session['datetime'])) ?></p>
                <p>👤 <?= htmlspecialchars($session['coach_name'] ?? 'TBD') ?></p>
                <p>📍 <?= htmlspecialchars($session['location']) ?></p>
                <p>💰 <strong>$<?= number_format($session['price'], 2) ?></strong> per person</p>
                <p>👥 <?= $session['spots_available'] ?> spots remaining</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="<?= $baseUrl ?>sessions/book/<?= $session['id'] ?>" method="POST">
                <div class="form-group">
                    <label for="student_id">Select Student *</label>
                    <select id="student_id" name="student_id" required>
                        <option value="">Choose a student</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['name']) ?> (<?= htmlspecialchars($student['level']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <a href="<?= $baseUrl ?>sessions/<?= $session['id'] ?>" class="btn-back">Cancel</a>
                    <button type="submit" class="btn btn-primary">Confirm Booking</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
