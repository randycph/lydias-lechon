<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
    
            // Check if user with this email already exists
            $user = User::where('email', $googleUser->getEmail())->first();
    
            if ($user) {
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                    ]);
                }
            } else {
                $user = User::create([
                    'firstname' => $googleUser->user['given_name'] ?? '',
                    'lastname' => $googleUser->user['family_name'] ?? '',
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'role_id' => 6,
                    'user_type' => 'customer',
                    'registration_source' => 'web',
                    'password' => bcrypt(str()->random(16)), // random password
                    'is_active' => 1,
                ]);
            }
    
            Auth::login($user);
    
            return redirect()->route('my-account')->with('success', 'Login successful!');
    
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['error' => 'Google login failed. Please try again.']);
        }
    }
    
}
