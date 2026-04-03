<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SurfManager - Learn to Surf</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/landing.css">
</head>

<body>
<header class="landing-header">
    <div class="container">
        <a href="<?= $baseUrl ?>" class="logo">Surf<span>Manager</span></a>
        <div class="nav-wrapper">
            <nav>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="<?= $baseUrl ?>dashboard" class="btn btn-primary">Go to Dashboard</a>
                    <a href="<?= $baseUrl ?>auth/logout">Logout</a>
                <?php else: ?>
                    <a href="<?= $baseUrl ?>login">Login</a>
                    <a href="<?= $baseUrl ?>signup" class="btn btn-primary">Sign Up</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>

    <section class="hero landing-hero">
        <div class="container">
            <h1>Catch Your First Wave</h1>
            <p>Professional surf lessons for all skill levels. Book your session today and ride the waves with expert coaches.</p>
            <div class="hero-buttons">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="<?= $baseUrl ?>dashboard" class="btn btn-primary btn-lg">Go to Dashboard</a>
                <?php else: ?>
                    <a href="<?= $baseUrl ?>signup" class="btn btn-primary btn-lg">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <main class="container">
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">🏄</div>
                <h3>Expert Coaches</h3>
                <p>Learn from certified surf instructors with years of experience in ocean safety and teaching.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🌊</div>
                <h3>All Levels Welcome</h3>
                <p>From complete beginners to advanced surfers, we have lessons tailored to your skill level.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📅</div>
                <h3>Flexible Booking</h3>
                <p>Book your sessions online anytime. Choose from morning or afternoon slots that fit your schedule.</p>
            </div>
        </div>

        <?php if (!isset($_SESSION['user'])): ?>
            <div class="cta-section">
                <h2>Ready to Start Your Surf Journey?</h2>
                <p>Join hundreds of students who have learned to surf with us.</p>
                <a href="<?= $baseUrl ?>signup" class="btn btn-primary btn-lg">Create Free Account</a>
            </div>
        <?php endif; ?>
    </main>

    <footer class="landing-footer">
        <div class="container">
            <p>&copy; 2026 SurfManager. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
