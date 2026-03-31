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

    <style>
        .home-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }
        .home-card {
            background: var(--white);
            border-radius: 24px;
            padding: 2.5rem;
            text-align: center;
            text-decoration: none;
            color: var(--text-dark);
            box-shadow: 0 10px 40px var(--shadow);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .home-card:hover {
            transform: translateY(-5px);
            border-color: var(--ocean-light);
            box-shadow: 0 20px 50px var(--shadow);
        }
        .home-card-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .home-card h2 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--ocean-blue);
        }
        .home-card p {
            color: var(--text-gray);
        }
    </style>
</body>

</html>
