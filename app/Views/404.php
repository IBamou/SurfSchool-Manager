<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <style>
        .error-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
            text-align: center;
            padding: 2rem;
        }
        .error-page h1 {
            font-size: 8rem;
            color: var(--ocean-blue);
            margin-bottom: 1rem;
        }
        .error-page h2 {
            font-size: 2rem;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        .error-page p {
            color: var(--text-gray);
            margin-bottom: 2rem;
        }
    </style>
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>" class="logo">Surf<span>Manager</span></a>
        </div>
    </header>

    <main class="container">
        <div class="error-page">
            <h1>404</h1>
            <h2>Oops! Page Not Found</h2>
            <p>The page you're looking for doesn't exist or has been moved.</p>
            <a href="<?= $baseUrl ?>" class="btn btn-primary">Go Home</a>
        </div>
    </main>
</body>

</html>