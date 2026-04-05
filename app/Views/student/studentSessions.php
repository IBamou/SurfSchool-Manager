<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Sessions - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/student-sessions.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>dashboard" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>dashboard">Dashboard</a>
                <a href="<?= $baseUrl ?>sessions" class="active">Sessions</a>
                <a href="<?= $baseUrl ?>profile">Profile</a>
                <form action="<?= $baseUrl ?>auth/logout" method="POST">
                    <button type="submit" class="btn btn-primary">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <section class="hero hero-sm">
        <div class="container">
            <h1>My Sessions</h1>
            <p>Your enrolled surf sessions</p>
        </div>
    </section>

    <main class="container">
        <div class="sessions-tabs">
            <button class="tab-btn active" data-tab="upcoming">Upcoming</button>
            <button class="tab-btn" data-tab="completed">Completed</button>
        </div>

        <?php if (!empty($sessions)): ?>
            <div class="sessions-list" id="upcoming">
                <?php foreach ($sessions as $session): ?>
                    <?php if ($session['datetime'] >= date('Y-m-d H:i:s')): ?>
                        <div class="session-card">
                            <div class="session-date">
                                <span class="date-day"><?= date('d', strtotime($session['datetime'])) ?></span>
                                <span class="date-month"><?= date('M', strtotime($session['datetime'])) ?></span>
                            </div>
                            <div class="session-info">
                                <h3><?= htmlspecialchars($session['lesson_title'] ?? '') ?></h3>
                                <p class="session-meta">
                                    <span>🕐 <?= date('H:i', strtotime($session['datetime'])) ?> (<?= $session['duration'] ?? 60 ?>min)</span>
                                    <span>📍 <?= htmlspecialchars($session['location'] ?? '') ?></span>
                                    <span>👤 <?= htmlspecialchars($session['coach_name'] ?? '') ?></span>
                                </p>
                            </div>
                            <div class="session-status">
                                <span class="badge badge-<?= $session['payment_status'] ?? 'pending' ?>">
                                    <?= ucfirst($session['payment_status'] ?? 'Pending') ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="sessions-list hidden" id="completed">
                <?php foreach ($sessions as $session): ?>
                    <?php if ($session['datetime'] < date('Y-m-d H:i:s')): ?>
                        <div class="session-card session-completed">
                            <div class="session-date">
                                <span class="date-day"><?= date('d', strtotime($session['datetime'])) ?></span>
                                <span class="date-month"><?= date('M', strtotime($session['datetime'])) ?></span>
                            </div>
                            <div class="session-info">
                                <h3><?= htmlspecialchars($session['lesson_title'] ?? '') ?></h3>
                                <p class="session-meta">
                                    <span>🕐 <?= date('H:i', strtotime($session['datetime'])) ?></span>
                                    <span>📍 <?= htmlspecialchars($session['location'] ?? '') ?></span>
                                </p>
                            </div>
                            <div class="session-status">
                                <span class="badge badge-completed">Completed</span>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">🏄</div>
                <h2>No Sessions Yet</h2>
                <p>Book your first surf session to get started!</p>
                <a href="<?= $baseUrl ?>dashboard" class="btn btn-primary">Browse Available Sessions</a>
            </div>
        <?php endif; ?>
    </main>

    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.sessions-list').forEach(l => l.classList.add('hidden'));
                
                this.classList.add('active');
                document.getElementById(this.dataset.tab).classList.remove('hidden');
            });
        });
    </script>
</body>

</html>
