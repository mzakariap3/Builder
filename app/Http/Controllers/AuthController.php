<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Demo-only frontend prototype login. Replace with real Laravel auth later.
        if ($credentials['email'] !== 'manager@odeon.com' || $credentials['password'] !== 'password') {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password demo tidak sesuai.']);
        }

        $request->session()->regenerate();
        $request->session()->put([
            'demo_logged_in' => true,
            'demo_name' => 'Odeon Manager',
            'demo_email' => $credentials['email'],
        ]);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
