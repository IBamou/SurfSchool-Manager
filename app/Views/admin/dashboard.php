<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/dashboard.css">
</head>

<body>
<header>
    <div class="container header-content">
        <a href="<?= $baseUrl ?>dashboard" class="logo">Surf<span>Manager</span></a>
        <div class="nav-wrapper">
            <nav>
                <a href="<?= $baseUrl ?>dashboard" class="active">Dashboard</a>
                <a href="<?= $baseUrl ?>lessons">Lessons</a>
                <a href="<?= $baseUrl ?>sessions">Sessions</a>
                <a href="<?= $baseUrl ?>students">Students</a>
                <a href="<?= $baseUrl ?>coaches">Coaches</a>
                <a href="<?= $baseUrl ?>profile">Profile</a>
                <form action="<?= $baseUrl ?>auth/logout" method="POST">
                    <button type="submit" class="btn btn-primary">Logout</button>
                </form>
            </nav>
        </div>
    </div>
</header>

    <section class="hero hero-sm">
        <div class="container">
            <h1>Dashboard</h1>
            <p>Manage your surf school operations</p>
        </div>
    </section>

    <main class="container">
        <div class="quick-stats">
            <div class="stat-box">
                <div class="stat-number"><?= $totalLessons ?? 0 ?></div>
                <div class="stat-label">Lessons</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?= $totalSessions ?? 0 ?></div>
                <div class="stat-label">Sessions</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?= $totalStudents ?? 0 ?></div>
                <div class="stat-label">Students</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?= $totalAssignments ?? 0 ?></div>
                <div class="stat-label">Bookings</div>
            </div>
        </div>

        <div class="dashboard-cards">
            <a href="<?= $baseUrl ?>lessons" class="dashboard-card">
                <div class="dashboard-icon">📚</div>
                <h3>Lessons</h3>
                <p>Manage surf lesson courses</p>
            </a>
            
            <a href="<?= $baseUrl ?>sessions" class="dashboard-card">
                <div class="dashboard-icon">📅</div>
                <h3>Sessions</h3>
                <p>View and schedule sessions</p>
            </a>
            
            <a href="<?= $baseUrl ?>students" class="dashboard-card">
                <div class="dashboard-icon">👥</div>
                <h3>Students</h3>
                <p>Manage student enrollments</p>
            </a>
            
            <a href="<?= $baseUrl ?>coaches" class="dashboard-card">
                <div class="dashboard-icon">🏄</div>
                <h3>Coaches</h3>
                <p>Manage instructors</p>
            </a>
        </div>
    </main>
</body>

</html>
