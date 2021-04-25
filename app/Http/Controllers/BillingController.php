<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    
    public function show()
    {
        return Inertia::render('Account/Billing/Show');
    }

}
