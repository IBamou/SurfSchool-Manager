<?php 
$baseUrl = 'http://localhost/surfManager/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | SurfManager</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e8f4f8 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .error-page {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
        }
        
        .error-container {
            max-width: 500px;
        }
        
        .error-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #0077B6 0%, #90E0EF 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            box-shadow: 0 20px 40px rgba(0, 119, 182, 0.2);
        }
        
        .error-icon svg {
            width: 60px;
            height: 60px;
            color: white;
        }
        
        .error-code {
            font-family: 'Montserrat', sans-serif;
            font-size: 6rem;
            font-weight: 800;
            background: linear-gradient(135deg, #0077B6 0%, #00B4D8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
        }
        
        .error-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: #03045E;
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: white;
            color: #0077B6;
            border: 2px solid #0077B6;
            border-radius: 8px;
            font-family: 'Open Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-back:hover {
            background: #0077B6;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 119, 182, 0.3);
        }
        
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #0077B6 0%, #00B4D8 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-family: 'Open Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 119, 182, 0.4);
        }
        
        .error-footer {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .error-footer p {
            font-size: 0.85rem;
            color: #94a3b8;
        }
        
        .error-footer a {
            color: #0077B6;
            text-decoration: none;
            font-weight: 600;
        }
        
        .error-footer a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .error-code {
                font-size: 4rem;
            }
            
            .error-title {
                font-size: 1.5rem;
            }
            
            .error-actions {
                flex-direction: column;
            }
            
            .btn-back,
            .btn-home {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>


    <main class="error-page">
        <div class="error-container">
            <div class="error-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            
            <div class="error-code">404</div>
            <h1 class="error-title">Oops! Page Not Found</h1>
            <p class="error-message">
                The page you're looking for doesn't exist or has been moved. 
                Let's get you back on track.
            </p>
            
            <div class="error-actions">
                <button onclick="goBack()" class="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Go Back
                </button>
                <a href="<?= $baseUrl ?>" class="btn-home">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Go to Home
                </a>
            </div>
            
            <div class="error-footer">
                <p>Need help? <a href="mailto:support@surfschool.com">Contact Support</a></p>
            </div>
        </div>
    </main>

    <script>
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '<?= $baseUrl ?>';
            }
        }
    </script>
</body>
</html>