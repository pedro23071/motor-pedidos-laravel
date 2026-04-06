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

        // 1. Buscar por provider_id
        $user = User::where('provider', 'github')
            ->where('provider_id', $githubUser->getId())
            ->first();

        // 2. Si no existe, buscar por email
        if (!$user) {
            $user = User::where('email', $githubUser->getEmail())->first();
        }

        // 3. Si no existe, crear nuevo
        if (!$user) {
            $user = new User();
        }

        // 4. Actualizar datos
        $user->name = $githubUser->getName() ?: $githubUser->getNickname();
        $user->email = $githubUser->getEmail();
        $user->provider = 'github';
        $user->provider_id = $githubUser->getId();
        $user->avatar = $githubUser->getAvatar();
        $user->save();

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