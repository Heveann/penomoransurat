<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->with('error', 'Username atau password salah')->withInput($request->only('email'));
        }

        // Redirect sesuai guard
        if (auth('admin')->check()) {
            return redirect()->intended(route('admin.dashboard-admin'));
        }
        if (auth('web')->check()) {
            return redirect()->intended(route('dashboard'));
        }
        abort(403);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
