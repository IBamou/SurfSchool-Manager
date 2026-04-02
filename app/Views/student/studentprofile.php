<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/profile.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>dashboard" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>dashboard">Dashboard</a>
                <a href="<?= $baseUrl ?>sessions">Sessions</a>
                <a href="<?= $baseUrl ?>profile" class="active">Profile</a>
                <a href="<?= $baseUrl ?>auth/logout">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">🏄</div>
                <div class="profile-info">
                    <h1><?= htmlspecialchars($user['name'] ?? 'Student') ?></h1>
                    <p class="profile-email"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                    <span class="badge badge-<?= strtolower($user['level'] ?? 'beginner') ?>">
                        <?= htmlspecialchars($user['level'] ?? 'Beginner') ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="profile-details">
            <div class="detail-section">
                <h2>Account Information</h2>
                <div class="detail-row">
                    <span class="detail-label">Member Since</span>
                    <span class="detail-value"><?= date('M d, Y', strtotime($user['created_at'] ?? 'now')) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Sessions</span>
                    <span class="detail-value"><?= $totalSessions ?? 0 ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Completed Payments</span>
                    <span class="detail-value">$<?= number_format($totalSpent ?? 0, 2) ?></span>
                </div>
            </div>
        </div>

        <div class="profile-actions">
            <a href="<?= $baseUrl ?>edit-profile" class="btn btn-secondary">Edit Profile</a>
            <a href="<?= $baseUrl ?>change-password" class="btn btn-outline">Change Password</a>
        </div>
    </main>
</body>

</html>
