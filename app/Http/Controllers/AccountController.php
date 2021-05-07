<?php

namespace App\Http\Controllers;

use App\RequestState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class AccountController extends Controller
{
    
    public function redirect(Request $request, $provider)
    {
        return Socialite::driver($provider)
            ->scopes([
                'read:user',
                'repo',
            ])
            ->with([
                'state' => RequestState::fromArray([
                    'redirect' => url()->previous(),
                ]),
            ])
            ->redirect();
    }

    public function callback(Request $request, $provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        $state = RequestState::fromString($request->get('state'));

        Auth::user()->accounts()->create([
            'identifier' => $user->nickname,
            'type' => $provider,
            'token' => $user->token,            
            'refresh_token' => $user->refreshToken,            
            'expires_at' => $user->expiresIn,            
        ]);

        return Redirect::to($state['redirect'])
            ->with('message', "Account connected!");
    }

}
