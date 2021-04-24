<?php

use App\Models\Team;
use Inertia\Inertia;
use App\Mail\Invited;
use App\Models\Invitation;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\InvitationsController;
use App\Http\Controllers\MembershipsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Shared/Home');
});


Route::prefix('settings')->middleware('auth')->group(function() {
    Route::patch('/account', [AccountController::class, 'update'])->name('settings.account.update');

    Route::get('/teams/{team}/memberships', [MembershipsController::class, 'store'])->name('settings.teams.memberships.store');
    Route::post('/teams/{team}/invitations', [InvitationsController::class, 'store'])->name('settings.teams.invitations.store');
    Route::delete('/teams/{team}/invitations/{invitation}', [InvitationsController::class, 'destroy'])->name('settings.teams.invitations.destroy');

    Route::get('/teams/{team}', [TeamController::class, 'show'])->name('settings.teams.show');
    Route::get('/teams', [TeamController::class, 'index'])->name('settings.teams.index');
    Route::post('/teams', [TeamController::class, 'store'])->name('settings.teams.store');
});

Route::get('/dashboard', function () {
    return Inertia::render('Shared/Dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/needs-verification', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified']);

Route::get('/needs-confirmation', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'password.confirm']);

Route::get('/mailable', function () {
    $team = Team::first();
    $invitation = Invitation::first();

    return new Invited($team, $invitation);
});

require __DIR__.'/auth.php';
