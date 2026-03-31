<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/form.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>/home">Home</a>
                <a href="<?= $baseUrl ?>/lessons">Lessons</a>
                <a href="<?= $baseUrl ?>/login" class="active">Login</a>
            </nav>
        </div>
    </header>

    <main class="container form-page">
        <div class="form-card">
            <h1>Create Account</h1>
            <p class="form-subtitle">Join our surf school today</p>

            <form action="<?= $baseUrl ?>/auth/signup" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" placeholder="John Doe" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" placeholder="your@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" placeholder="Min 6 characters" required>
                    </div>

                    <div class="form-group">
                        <label for="level">Surf Level *</label>
                        <select id="level" name="level" required>
                            <option value="">Select Your Level</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                            <option value="Expert">Expert</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="<?= $baseUrl ?>/login" class="btn-back">Already have account?</a>
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
