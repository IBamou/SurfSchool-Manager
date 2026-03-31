<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($user['name'] ?? 'User') ?> - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/surf-theme.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>/home">Home</a>
                <a href="<?= $baseUrl ?>/lessons">Lessons</a>
                <a href="<?= $baseUrl ?>/sessions">Sessions</a>
                <a href="<?= $baseUrl ?>/students" class="active">Students</a>
            </nav>
        </div>
    </header>

    <main class="container page-content">
        <h2><?= htmlspecialchars($user['name'] ?? '') ?></h2>
        <p>Email: <?= htmlspecialchars($user['email'] ?? '') ?></p>
        <p>Level: <?= htmlspecialchars($user['level'] ?? '') ?></p>
        <a href="<?= $baseUrl ?>/students" class="btn btn-secondary">Back to Students</a>
    </main>
</body>

</html>
