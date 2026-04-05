<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
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
                <form action="<?= $baseUrl ?>auth/logout" method="POST">
                    <button type="submit">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container page-content">
        <h1>Users</h1>
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    <h2><?= htmlspecialchars($user['name']) ?></h2>
                    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
                    <p>Level: <?= htmlspecialchars($user['level']) ?></p>
                    <a href="<?= $baseUrl ?>students/<?= $user['id'] ?>">View</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>

</html>
