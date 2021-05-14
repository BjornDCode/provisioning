<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Pipeline\CreateForgeAccountRequest;

class ForgeAccountController extends Controller
{

    public function store(CreateForgeAccountRequest $request)
    {
        Auth::user()->accounts()->create([
            'type' => 'forge',
            'email' => Auth::user()->email,
            'identifier' => $request->input('name'),
            'token' => Crypt::encryptString(
                $request->input('key')
            ),
        ]);
        
        return Redirect::back()->with('message', 'Forge account created.');
    }
    
}
