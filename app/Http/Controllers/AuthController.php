<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'checkAuth']);
        $this->middleware('throttle:6,1')->only(['login', 'register', 'adminLogin']);
        $this->middleware('auth')->only('checkAuth');
    }

    /**
     * Check authentication status
     */
    public function checkAuth()
    {
        return response()->json(['authenticated' => Auth::check()]);
    }

    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    /**
     * Show the admin login form
     */
    public function showAdminLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->is_admin 
                ? redirect()->intended('/admin/dashboard')
                : redirect()->route('home');
        }
        return view('auth.admin-login');
    }

    /**
     * Show the registration form
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => false,
            ]);

            Auth::login($user);
            return redirect()->route('home');
        } catch (\Exception $e) {
            Log::error('Registration failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['email' => 'Registration failed. Please try again.']);
        }
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        try {
            $credentials = $request->only('email', 'password');
            
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                
                if (Auth::user()->is_admin) {
                    return redirect()->intended(route('admin.dashboard'));
                }
                
                return redirect()->intended(route('home'));
            }

            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        } catch (\Exception $e) {
            Log::error('Login failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['email' => 'Login failed. Please try again.']);
        }
    }

    /**
     * Handle admin login
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
                $user = Auth::user();
                
                if (!$user->is_admin) {
                    Auth::logout();
                    throw ValidationException::withMessages([
                        'email' => ['This account does not have admin privileges.'],
                    ]);
                }

                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        } catch (\Exception $e) {
            Log::error('Admin login failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['email' => 'Admin login failed. Please try again.']);
        }
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
