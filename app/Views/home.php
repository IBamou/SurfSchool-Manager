<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surf Manager - Home</title>
    <link rel="stylesheet" href="<?= $baseUrl ?? '' ?>/app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?? '' ?>/app/Views/css/home.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?? '' ?>" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?? '' ?>/home" class="active">Home</a>
                <a href="<?= $baseUrl ?? '' ?>/lessons">Lessons</a>
                <a href="<?= $baseUrl ?? '' ?>/sessions">Sessions</a>
                <a href="<?= $baseUrl ?? '' ?>/students">Students</a>
                <a href="<?= $baseUrl ?? '' ?>/login">Login</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Welcome to Surf Manager</h1>
            <p>Your complete surf school management system</p>
        </div>
    </section>

    <main class="container">
        <div class="home-cards">
            <a href="<?= $baseUrl ?? '' ?>/lessons" class="home-card">
                <div class="home-card-icon">📚</div>
                <h2>Lessons</h2>
                <p>Browse and manage surf lesson courses</p>
            </a>
            
            <a href="<?= $baseUrl ?? '' ?>/sessions" class="home-card">
                <div class="home-card-icon">📅</div>
                <h2>Sessions</h2>
                <p>View scheduled sessions and book spots</p>
            </a>
            
            <a href="<?= $baseUrl ?? '' ?>/students" class="home-card">
                <div class="home-card-icon">👥</div>
                <h2>Students</h2>
                <p>Manage students and their enrollments</p>
            </a>
        </div>
    </main>
</body>

</html>
