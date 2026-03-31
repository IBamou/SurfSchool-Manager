<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/surf-theme.css">
    <style>
        .level-modal {
            display: none;
            padding: 1rem;
            background: var(--off-white);
            border-radius: 12px;
            margin-top: 1rem;
        }

        .level-modal.active {
            display: block;
        }
    </style>
</head>
<body>
    <main class="container" style="padding-top: 2rem;">
        <h2><?= htmlspecialchars($user['name'] ?? '') ?></h2>
        <p>Email: <?= htmlspecialchars($user['email'] ?? '') ?></p>
        <p>Level: <?= htmlspecialchars($user['level'] ?? '') ?></p>
        <button class="btn btn-secondary" onclick="showPopup()">Update Level</button>

        <div id="updateLevelModal" class="level-modal">
            <div>
                <select name="level" id="levelSelect">
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                    <option value="Expert">Expert</option>
                </select>
            </div>
        </div>
    </main>
    <script>
        function showPopup() {
            const modal = document.getElementById('updateLevelModal');
            modal.classList.add('active');
        }
    </script>
</body>
</html>
