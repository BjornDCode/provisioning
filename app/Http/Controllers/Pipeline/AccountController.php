<?php

namespace App\Http\Controllers\Pipeline;

use App\RequestState;
use App\Enums\GitProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class AccountController extends Controller
{
    
    public function redirect(Request $request, $provider)
    {
        return Socialite::driver($provider)
            ->scopes($this->getScopes($provider))
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
            'email' => $user->email,
            'type' => $provider,
            'token' => $user->token,            
            'refresh_token' => $user->refreshToken,            
            'expires_at' => $user->expiresIn,            
        ]);

        return Redirect::to($state['redirect'])
            ->with('message', "Account connected!");
    }

    public function getScopes($provider)
    {
        return match($provider) {
            GitProvider::GITHUB => [
                'read:user',
                'repo',
                'delete_repo',
            ],
            default => [],
        };        
    }

}
