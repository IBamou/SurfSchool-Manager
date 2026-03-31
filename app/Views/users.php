<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    a {
        display: block;
        width: 35px;
        padding: 15px;
        background-color: gray;
        text-decoration: none;
        color: white;
    }

    main {
        margin-top: 20px;
    }
</style>

<body>
    <main>
        <h1>Users</h1>
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                    <p>Level: <?php echo htmlspecialchars($user['level']); ?></p>
                    <a href="http://localhost/surfManager/users/<?= $user['id'] ?>">View</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>

</html>