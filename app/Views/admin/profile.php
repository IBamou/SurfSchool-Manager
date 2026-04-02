<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/profile.css">
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
                <a href="<?= $baseUrl ?>coaches">Coaches</a>
                <a href="<?= $baseUrl ?>profile" class="active">Profile</a>
                <a href="<?= $baseUrl ?>auth/logout">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">👤</div>
                <div class="profile-info">
                    <h1><?= htmlspecialchars($user['name'] ?? 'Admin') ?></h1>
                    <p class="profile-email"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                    <span class="badge badge-admin">Administrator</span>
                </div>
            </div>
        </div>

        <div class="profile-details">
            <div class="detail-section">
                <h2>Account Information</h2>
                <div class="detail-row">
                    <span class="detail-label">Role</span>
                    <span class="detail-value"><?= htmlspecialchars($user['role'] ?? 'admin') ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Member Since</span>
                    <span class="detail-value"><?= date('M d, Y', strtotime($user['created_at'] ?? 'now')) ?></span>
                </div>
            </div>
        </div>

        <div class="profile-actions">
            <a href="<?= $baseUrl ?>auth/edit-profile" class="btn btn-secondary">Edit Profile</a>
            <a href="<?= $baseUrl ?>auth/change-password" class="btn btn-outline">Change Password</a>
        </div>
    </main>
</body>

</html>
