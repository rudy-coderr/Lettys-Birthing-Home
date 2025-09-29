<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Unauthorized - Letty's Birthing Home</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="img/imglogo.png">

    <style>
        :root {
            --primary-color: #113F67;
            --primary-dark: #0d2f4d;
            --primary-light: #1a4d7a;
            --primary-gradient: linear-gradient(135deg, #113F67 0%, #0d2f4d 100%);
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --light-bg: #f8fafc;
            --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.15), 0 4px 10px rgba(0, 0, 0, 0.1);
            --card-shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.2), 0 10px 20px rgba(0, 0, 0, 0.15);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .unauthorized-card {
            max-width: 500px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 40px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .unauthorized-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-radius: var(--border-radius);
            z-index: -1;
        }

        .login-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-top-right-radius: var(--border-radius);
            border-bottom-right-radius: var(--border-radius);
            border-left: 1px solid rgba(255, 255, 255, 0.3);
        }

        .logo-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .logo-container img {
            width: 80px;
            height: 80px;
            border-radius: var(--border-radius);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        .system-title {
            font-size: 1.5rem;
            font-weight: 600;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0;
        }

        .error-title {
            font-size: 4rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 20px 0 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .error-subtitle {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .error-message {
            font-size: 1rem;
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .btn-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 140px;
        }

        .btn-return {
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            backdrop-filter: blur(5px);
        }

        .btn-return:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(17, 63, 103, 0.4);
        }

        .btn-login {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(17, 63, 103, 0.4);
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(17, 63, 103, 0.6);
            color: white;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .unauthorized-card {
                padding: 30px 25px;
                max-width: 100%;
            }

            .error-title {
                font-size: 3rem;
            }

            .error-subtitle {
                font-size: 1.5rem;
            }

            .system-title {
                font-size: 1.3rem;
            }

            .btn-container {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 250px;
            }

            .logo img {
                width: 70px;
                height: 70px;
            }
        }

        @media (max-width: 480px) {
            .unauthorized-card {
                padding: 25px 20px;
            }

            .error-title {
                font-size: 2.5rem;
            }

            .error-subtitle {
                font-size: 1.3rem;
            }

            .error-message {
                font-size: 0.9rem;
            }

            .system-title {
                font-size: 1.2rem;
            }
        }

        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(135deg, #2d3748 0%, #4a5568 50%, #1a202c 100%);
            }

            .unauthorized-card {
                background: rgba(26, 32, 44, 0.95);
                color: white;
            }

            .error-message {
                color: #cbd5e0;
            }

            .error-subtitle {
                color: var(--primary-light);
            }

            .btn-return {
                background: rgba(45, 55, 72, 0.9);
                color: var(--primary-light);
                border-color: var(--primary-light);
            }

            .btn-return:hover {
                background: var(--primary-light);
                color: white;
            }

            .system-title {
                background: linear-gradient(135deg, #4299e1 0%, #63b3ed 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .error-title {
                background: linear-gradient(135deg, #4299e1 0%, #63b3ed 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Subtle animation for the card */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .unauthorized-card {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Icon animation */
        .logo img {
            transition: var(--transition);
        }

        .logo img:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="unauthorized-card">
        <div class="logo-container">
            <div class="logo">
                <img src="{{ asset('img/imglogo.png') }}" alt="Letty's Birthing Home Logo"
                    class="rounded-circle shadow-sm">
            </div>
            <h2 class="system-title">Letty's Birthing Home</h2>
        </div>
        <h1 class="error-title">403</h1>
        <h3 class="error-subtitle">Unauthorized Access</h3>
        <p class="error-message">
            You don't have permission to access this page. Please sign in with an authorized account or contact the
            administrator for assistance.
        </p>
        <div class="btn-container">
            <a href="javascript:history.back()" class="btn btn-return">
                <i class="fas fa-arrow-left me-2"></i>Go Back
            </a>
            <a href="/login" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Go to Login
            </a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Prevent browser cache from reloading stale page
        if (performance.navigation.type === 2) {
            location.reload(true);
        }

        // Add subtle hover effects to buttons
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>

</html>
