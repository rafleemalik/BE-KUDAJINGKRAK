<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'user', // default role is 'user'
        ]);

        return redirect('/login')->with('success', 'Register berhasil! Silahkan login.');
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // Attempt to log in with provided credentials
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with([
                    'auth' => [
                        'user' => [
                            'id' => $user->id,
                            'username' => $user->username,
                            'name' => $user->name_web || $user->username,
                            'role' => $user->role
                        ]
                    ]
                ]);
            }

            return redirect()->route('ferrari')->with([
                'auth' => [
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'name' => $user->name_web || $user->username,
                        'role' => $user->role
                    ]
                ]
            ]);
        }

        return back()->withErrors(['username' => 'Invalid credentials']);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
