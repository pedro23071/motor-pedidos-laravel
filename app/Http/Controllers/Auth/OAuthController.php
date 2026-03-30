<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::updateOrCreate(
            [
                'provider' => 'github',
                'provider_id' => $githubUser->getId(),
            ],
            [
                'name' => $githubUser->getName() ?: $githubUser->getNickname(),
                'email' => $githubUser->getEmail(),
                'avatar' => $githubUser->getAvatar(),
            ]
        );

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}