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

        // Check if the credentials match 'admin' and 'admin123'
        if ($credentials['username'] == 'admin' && $credentials['password'] == 'admin123') {
            // Find the admin user and update the role
            $user = User::where('username', 'admin')->first();
            
            if ($user) {
                // Update the role to 'admin'
                $user->role = 'admin';
                $user->save();

                // Log in the user
                Auth::login($user);

                // Redirect to the admin dashboard
                return redirect()->route('admin.dashboard');
            }
        }

        // Attempt to log in with provided credentials
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Set default profile picture dan nama untuk user Amira
            if ($user->username === 'Amira' && !$user->profile_photo) {
                // Copy default avatar ke storage
                $defaultAvatar = public_path('images/default-avatar.png');
                if (file_exists($defaultAvatar)) {
                    $path = 'public/profile-photos/default-avatar.png';
                    Storage::put($path, file_get_contents($defaultAvatar));
                    $user->profile_photo = 'default-avatar.png';
                    $user->save();
                }
            }

            return redirect()->route('ferrari')->with([
                'auth' => [
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'name' => $user->name_web || $user->username,
                        'role' => $user->role,
                        'profile_photo' => $user->profile_photo ? Storage::url('profile-photos/' . $user->profile_photo) : null
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

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // Update name_web saja, username tetap
            $user->name_web = $request->name;
            
            // Handle upload foto profil
            if ($request->hasFile('profile_photo')) {
                \Log::info('Starting photo upload process');
                \Log::info('File received: ' . $request->file('profile_photo')->getClientOriginalName());
                
                // Hapus foto lama jika ada
                if ($user->profile_photo) {
                    \Log::info('Deleting old photo: ' . $user->profile_photo);
                    Storage::delete('profile-photos/' . $user->profile_photo);
                }
                
                // Generate nama file yang unik
                $filename = time() . '_' . $request->file('profile_photo')->getClientOriginalName();
                \Log::info('Generated filename: ' . $filename);
                
                // Simpan foto baru
                try {
                    $path = $request->file('profile_photo')->storeAs(
                        'profile-photos',
                        $filename
                    );
                    \Log::info('File stored at: ' . $path);
                } catch (\Exception $e) {
                    \Log::error('Error storing file: ' . $e->getMessage());
                    throw $e;
                }
                
                $user->profile_photo = $filename;
            }

            $user->save();
            \Log::info('User profile updated successfully');

            // Redirect ke halaman ferrari dengan Inertia::location
            return Inertia::location(route('ferrari'));
        } catch (\Exception $e) {
            \Log::error('Profile update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update profile: ' . $e->getMessage()]);
        }
    }

    public function ferrari()
    {
        $user = auth()->user();
        
        return Inertia::render('User/Ferrari', [
            'auth' => [
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'name' => $user->name_web,
                    'role' => $user->role,
                    'profile_photo' => $user->profile_photo ? asset('storage/profile-photos/' . $user->profile_photo) : null
                ]
            ]
        ]);
    }
}
