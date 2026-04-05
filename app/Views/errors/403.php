<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - SurfManager</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
    </style>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .error-container {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .error-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
        }
        h1 {
            color: #dc2626;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        p {
            color: #6b7280;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-block;
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(245, 87, 108, 0.4);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">🔒</div>
        <h1>Access Denied</h1>
        <p>You don't have permission to access this page. Please contact an administrator if you believe this is an error.</p>
        <a href="javascript:history.back()" class="btn">← Go Back</a>
    </div>
</body>
</html>
