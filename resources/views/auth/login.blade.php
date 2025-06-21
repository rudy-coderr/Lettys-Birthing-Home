<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/imglogo.png') }}">
    <style>
        body {
            background: linear-gradient(to bottom,rgb(50, 134, 104) 0%, #2d5a3d 50%, #0f2419 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 35px 45px;
            width: 100%;
            max-width: 450px;
            border: none;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo img {
            width: 55px;
            height: 55px;
            margin-bottom: 18px;
            /* Removed border-radius and background - now transparent */
        }

        .system-title {
            color: #52b788;
            font-size: 22px;
            font-weight: 600;
            margin: 0 0 12px 0;
            letter-spacing: 0.5px;
        }

        .login-title {
            color: #666;
            font-size: 16px;
            font-weight: 400;
            margin: 0;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background: white;
            border-color: #52b788;
            box-shadow: 0 0 0 3px rgba(82, 183, 136, 0.1);
            outline: none;
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 4px;
        }

        .password-toggle:hover {
            color: #52b788;
        }

        .btn-login {
            background: #52b788;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            padding: 12px;
            width: 100%;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            background: #40916c;
            color: white;
            transform: translateY(-1px);
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
            color: #666;
            font-size: 14px;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
            z-index: 1;
        }

        .divider span {
            background: white;
            padding: 0 16px;
            position: relative;
            z-index: 2;
        }

        .btn-google {
            background: #52b788;
            border: 1px solid #52b788;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            padding: 12px;
            width: 100%;
            font-size: 14px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-google:hover {
            background: #40916c;
            border-color: #40916c;
            color: white;
            transform: translateY(-1px);
            text-decoration: none;
        }

        .google-icon {
            width: 18px;
            height: 18px;
        }

        @media (max-width: 768px) {
            .login-card {
                padding: 30px 24px;
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-container">
                <div class="logo">
                    <img src="{{ asset('img/imglogo.png') }}" alt="Logo">
                </div>
                <h2 class="system-title">Letty's Birthing Home</h2>
                <h1 class="login-title">Login to your account</h1>
            </div>

            <form method="POST" action="/login">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email"
                           placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-container">
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password"
                               placeholder="Enter your password">
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    Login
                </button>
            </form>

            <div class="divider">
                <span>or</span>
            </div>

            <a href="#" class="btn btn-google">
                <svg class="google-icon" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Login with Google
            </a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>