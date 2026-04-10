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
            padding: 12px 10px;
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

        .floating-label input:focus + label,
        .floating-label input:not(:placeholder-shown) + label {
            top: -6px;
            font-size: 12px;
            color: #185a9d;
        }

        /* Animasi tombol */
        button {
            transition: 0.3s;
        }

    </style>

    <div class="text-center mb-4">
        <h2 class="text-2xl font-bold text-gray-700">Register</h2>
    </div>

    @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 10px 14px; margin-bottom: 1rem;">
            <p style="color: #dc2626; font-weight: 600; font-size: 0.85rem; margin: 0 0 6px;">❌ Registrasi Gagal!</p>
            <ul style="margin: 0; padding-left: 1.2rem;">
                @foreach ($errors->all() as $error)
                    <li style="color: #b91c1c; font-size: 0.82rem;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="floating-label">
            <input id="name" type="text" name="name" placeholder=" " value="{{ old('name') }}" required autofocus autocomplete="name" />
            <label for="name">Name</label>
        </div>

        <!-- Email Address -->
        <div class="mt-4 floating-label">
            <input id="email" type="email" name="email" placeholder=" " value="{{ old('email') }}" required autocomplete="username" />
            <label for="email">Email</label>
        </div>

        <!-- Password -->
        <div class="mt-4 floating-label">
            <input id="password" type="password" name="password" placeholder=" " required autocomplete="new-password" />
            <label for="password">Password</label>
            <span class="toggle-password" onclick="togglePassword('password', 'eyeIcon')" style="position:absolute;top:50%;right:12px;transform:translateY(-50%);cursor:pointer;color:#6b7280;font-size:18px;">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </span>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4 floating-label">
            <input id="password_confirmation" type="password" name="password_confirmation" placeholder=" " required autocomplete="new-password" />
            <label for="password_confirmation">Confirm Password</label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                Already registered?
            </a>

            <x-primary-button class="ms-4">
                Register
            </x-primary-button>
        </div>
    </form>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
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
