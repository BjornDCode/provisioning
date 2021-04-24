<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UpdateAccountRequest;

class AccountController extends Controller
{
    
    public function update(UpdateAccountRequest $request)
    {
        Auth::user()->update(
            $request->validated(),
        );

        return Redirect::route('dashboard');
    }

}
