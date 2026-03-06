<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // Determine login field type
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : (preg_match('/^\+?[0-9]{10,15}$/', $login) ? 'phone' : 'ppb_registration_no');

        if (Auth::attempt([$field => $login, 'password' => $password, 'is_active' => true], $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Update last login
            Auth::user()->update(['last_login_at' => now()]);

            // Redirect based on role
            if (Auth::user()->hasAnyRole(['Super Admin', 'Admin', 'Finance', 'Branch Admin', 'Committee Admin'])) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('portal.dashboard'));
        }

        return back()->withErrors(['login' => 'The provided credentials do not match our records.'])->withInput($request->only('login'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
