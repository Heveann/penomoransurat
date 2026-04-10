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
            padding: 12px 40px 12px 10px; /* kasih ruang untuk ikon mata */
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

        .floating-label input:focus + label,
        .floating-label input:not(:placeholder-shown) + label {
            top: -6px;
            font-size: 12px;
            color: #6366f1;
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
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }
    </style>
    
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Judul Reset Password -->
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-700">Reset Password</h2>
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
@endsection
