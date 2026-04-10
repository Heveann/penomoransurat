<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            font-family: 'Figtree', sans-serif;
            /* Green-Blue Gradient */
            background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.95);
            /* Optional: Glassmorphism effect
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            */
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 10;
        }

        h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 700;
            color: #185a9d; /* Match the darker gradient tone */
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
            background: #f9f9f9;
        }

        .form-group input:focus {
            border-color: #43cea2;
            box-shadow: 0 0 0 4px rgba(67, 206, 162, 0.1);
            background: #fff;
        }

        .form-group label {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: #999;
            font-size: 15px;
            transition: 0.2s ease-out;
            pointer-events: none;
            background: transparent;
        }

        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label {
            top: -10px;
            left: 12px;
            font-size: 12px;
            color: #185a9d;
            background: #fff;
            padding: 0 6px;
            font-weight: 600;
            border-radius: 4px;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(to right, #43cea2, #185a9d);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 1rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(24, 90, 157, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            transition: color 0.3s;
        }
        
        .toggle-password:hover {
            color: #185a9d;
        }

        /* Clean Look - Removed Waves & Bubbles */
    </style>
</head>
<body>

    <div class="login-container">
        @yield('content')
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");
            if (passwordInput && eyeIcon) {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    eyeIcon.classList.remove("bi-eye");
                    eyeIcon.classList.add("bi-eye-slash");
                } else {
                    passwordInput.type = "password";
                    eyeIcon.classList.remove("bi-eye-slash");
                    eyeIcon.classList.add("bi-eye");
                }
            }
        }
    </script>
</body>
</html>
