@extends('layouts.auth')

@section('content')
    <style>
        /* Animasi fade-in untuk form */
        form {
            animation: fadeIn 0.8s ease-in-out;
            transform: translateY(20px);
            opacity: 0;
            animation-fill-mode: forwards;
        }

        @keyframes fadeIn {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Efek floating label */
        .floating-label {
            position: relative;
        }

        .floating-label input {
            padding: 12px 40px 12px 10px;
            /* kasih ruang untuk ikon mata */
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
            width: 100%;
        }

        .floating-label label {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            background: white;
            padding: 0 6px;
            color: #9ca3af;
            transition: 0.3s;
            pointer-events: none;
        }

        .floating-label input:focus,
        .floating-label input:not(:placeholder-shown) {
            border-color: #43cea2;
        }

        .floating-label input:focus+label,
        .floating-label input:not(:placeholder-shown)+label {
            top: -6px;
            font-size: 12px;
            color: #185a9d;
        }

        /* Tombol toggle password */
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            font-size: 18px;
        }

        /* Animasi tombol */
        button {
            transition: 0.3s;
        }

        button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(67, 206, 162, 0.4);
        }
    </style>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Logo -->
    <div class="text-center mb-4">
        <img src="{{ asset('img/logo-distanbun.png') }}" alt="Logo" class="mx-auto mb-2" style="width: 180px; height: auto;">
    </div>

    {{-- Teks: Register berhasil --}}
    @if(session('status'))
        <div
            style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px; padding: 10px 14px; margin-bottom: 1rem; text-align: center;">
            <span style="color: #16a34a; font-weight: 600; font-size: 0.85rem;">✅ {{ session('status') }}</span>
        </div>
    @endif

    {{-- Teks: Login gagal --}}
    @if(session('error'))
        <div
            style="background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 10px 14px; margin-bottom: 1rem; text-align: center;">
            <span style="color: #dc2626; font-weight: 600; font-size: 0.85rem;">❌ Username atau password salah.</span>
        </div>
    @endif





    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mt-4 floating-label">
            <input id="email" type="email" name="email" placeholder=" " value="{{ old('email') }}" required autofocus
                autocomplete="username" />
            <label for="email">Email</label>
        </div>

        <!-- Password -->
        <div class="mt-4 floating-label">
            <input id="password" type="password" name="password" placeholder=" " required autocomplete="current-password" />
            <label for="password">Password</label>
            <span class="toggle-password" onclick="togglePassword()">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </span>
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif

            <x-primary-button>
                Log in
            </x-primary-button>
        </div>


    </form>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");
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
    </script>
@endsection