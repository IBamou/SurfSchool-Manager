<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= $siteName ?? 'SurfManager' ?></title>
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
        <div class="form-card form-card-sm">
            <h1>Welcome Back</h1>
            <p class="form-subtitle">Sign in to your account</p>

            <form action="<?= $baseUrl ?>/auth/login" method="POST">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="your@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" placeholder="Your password" required>
                </div>

                <div class="form-actions">
                    <a href="<?= $baseUrl ?>/signup" class="btn-back">Create Account</a>
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
