<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class SocialConnectController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')
            ->scopes(['pages_show_list', 'pages_messaging', 'pages_read_engagement', 'pages_manage_metadata'])
            ->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::driver('facebook')->user();
        session(['user_token' => $user->token]);
        return redirect('/dashboard');
    }
}
