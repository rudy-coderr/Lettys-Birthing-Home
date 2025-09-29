<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Letty's Birthing Home</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/imglogo.png') }}">

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

        .login-wrapper {
            display: flex;
            max-width: 900px;
            width: 100%;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .welcome-section,
        .login-section {
            flex: 1;
            padding: 40px;
        }

        .welcome-section {
            background: var(--primary-gradient);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-top-left-radius: var(--border-radius);
            border-bottom-left-radius: var(--border-radius);
            position: relative;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-radius: var(--border-radius) 0 0 var(--border-radius);
        }

        .welcome-section>* {
            position: relative;
            z-index: 1;
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

        .welcome-section h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .welcome-section p {
            font-size: 1rem;
            opacity: 0.95;
            line-height: 1.6;
            margin-bottom: 10px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .login-section h2 {
            font-size: 1.8rem;
            font-weight: 600;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .login-section p {
            font-size: 0.9rem;
            color: #4a5568;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-control {
            border: 2px solid rgba(226, 232, 240, 0.8);
            border-radius: var(--border-radius);
            padding: 14px 16px;
            font-size: 0.95rem;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(17, 63, 103, 0.25);
            outline: none;
            background: rgba(255, 255, 255, 1);
        }

        .form-control::placeholder {
            color: #718096;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #718096;
            font-size: 1.2rem;
            padding: 4px;
            border-radius: 4px;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--primary-color);
            background: rgba(17, 63, 103, 0.1);
        }

        .form-check {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .form-check-input {
            margin-right: 8px;
        }

        .form-check-label {
            font-size: 0.9rem;
            color: #4a5568;
        }

        .btn-login {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 14px;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(17, 63, 103, 0.4);
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(17, 63, 103, 0.6);
        }

        .links {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
        }

        .links a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
        }

        .links a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
                padding: 10px;
            }

            .login-wrapper {
                flex-direction: column;
                max-width: 100%;
            }

            .welcome-section,
            .login-section {
                padding: 25px;
                border-radius: var(--border-radius);
            }

            .welcome-section {
                border-radius: var(--border-radius) var(--border-radius) 0 0;
            }

            .login-section {
                border-radius: 0 0 var(--border-radius) var(--border-radius);
                border-left: none;
                border-top: 1px solid rgba(255, 255, 255, 0.3);
            }

            .welcome-section h1 {
                font-size: 1.5rem;
            }

            .welcome-section p,
            .login-section p {
                font-size: 0.85rem;
            }

            .login-section h2 {
                font-size: 1.5rem;
            }
        }

        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(135deg, #2d3748 0%, #4a5568 50%, #1a202c 100%);
            }

            .welcome-section {
                background: linear-gradient(135deg, #113F67 0%, #0d2f4d 100%);
            }

            .login-section {
                background: rgba(26, 32, 44, 0.95);
                color: white;
            }

            .form-control {
                background: rgba(45, 55, 72, 0.9);
                color: white;
                border-color: rgba(255, 255, 255, 0.2);
            }

            .form-control:focus {
                background: rgba(45, 55, 72, 1);
                border-color: var(--primary-light);
                color: white;
            }

            .form-control::placeholder {
                color: #a0aec0;
            }

            .form-check-label {
                color: #e2e8f0;
            }

            .links a {
                color: var(--primary-light);
            }

            .links a:hover {
                color: #ffffff;
            }

            .password-toggle {
                color: #e2e8f0;
            }

            .login-section h2 {
                background: linear-gradient(135deg, #4299e1 0%, #63b3ed 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .login-section p {
                color: #cbd5e0;
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
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="welcome-section">
            <div class="logo-container">
                <img src="{{ asset('img/imglogo.png') }}" alt="Letty's Birthing Home Logo"
                    class="rounded-circle shadow-sm">
            </div>
            <h1>Welcome to Letty's Birthing Home</h1>
            <p>Supporting our staff and administrators in providing exceptional care for expectant mothers and their
                families.</p>
            <p>Log in to access your dashboard to manage appointments, monitor patient care, and coordinate daily
                operations.</p>

        </div>
        <div class="login-section">
            <h2>Login</h2>
            <p>Welcome! Login to access exclusive features and manage your account.</p>
            <form method="POST" action="/login">
                @csrf
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" required
                        aria-label="Email">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password"
                        required aria-label="Password">
                    <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="form-group mt-3">
                    {!! NoCaptcha::display() !!}
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                    @endif
                </div>

                <button type="submit" class="btn-login">LOGIN</button>
                <div class="links mt-3">
                    <a href="/forgot-password" class="forgot-password">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
    {!! NoCaptcha::renderJs() !!}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        @if (session('swal'))
            Swal.fire({
                icon: '{{ session('swal.icon') }}',
                title: '{{ session('swal.title') }}',
                text: '{{ session('swal.text') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: 'var(--primary-color)'
            });
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.password-toggle');
            const passwordInput = document.querySelector('#password');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>

</html>
