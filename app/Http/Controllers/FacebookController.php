<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class FacebookController extends Controller
{
public function redirectoFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();
    
            // Check if user with this email already exists
            $user = User::where('email', $facebookUser->getEmail())->first();
    
            if ($user) {
                if (!$user->facebook_id) {
                    $user->update([
                        'facebook_id' => $facebookUser->getId(),
                    ]);
                }
            } else {
                $user = User::create([
                    'firstname' => $facebookUser?->user['given_name'] ?? '',
                    'lastname' => $facebookUser?->user['family_name'] ?? '',
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'facebook_id' => $facebookUser->getId(),
                    'password' => bcrypt(str()->random(16)),
                    'is_active' => 1,
                ]);
            }
    
            Auth::login($user);
    
            return redirect()->route('my-account')->with('success', 'Login successful!');
    
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['error' => 'Google login failed. Please try again.']);
        }
    }

    public function handleFacebookDelete()
    {
        return redirect()->route('my-account')->withErrors(['error' => 'Facebook login is not supported for account deletion.']);
    }
}
