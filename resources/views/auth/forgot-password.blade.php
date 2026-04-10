@extends('layouts.auth')

@section('content')
    <!-- Logo -->
    <div class="text-center mb-4">
        <img src="{{ asset('img/logo-distanbun.png') }}" alt="Logo" class="mx-auto mb-2" style="width: 180px; height: auto;">
    </div>
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
            border-color: #6366f1;
        }

        .floating-label input:focus+label,
        .floating-label input:not(:placeholder-shown)+label {
            top: -6px;
            font-size: 12px;
            color: #6366f1;
        }

        /* Animasi tombol */
        button {
            transition: 0.3s;
        }

        button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }
    </style>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="floating-label">
            <input id="email" type="email" name="email" placeholder=" " value="{{ old('email') }}" required autofocus />
            <label for="email">Email</label>
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900">Back</a>
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
@endsection