<?php

use Inertia\Inertia;
use App\Mail\Invited;
use App\Models\Account\Team;
use App\Models\Account\Invitation;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController as ExternalAccountController;
use App\Http\Controllers\Account\TeamController;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\BillingController;
use App\Http\Controllers\Pipeline\PipelineController;
use App\Http\Controllers\Account\InvitationsController;
use App\Http\Controllers\Account\MembershipsController;
use App\Http\Controllers\Pipeline\StepConfigurationController;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/accounts/{provider}/redirect', [ExternalAccountController::class, 'redirect'])->name('accounts.redirect');
    Route::get('/accounts/{provider}/callback', [ExternalAccountController::class, 'callback'])->name('accounts.callback');

    Route::get('/pipelines/{pipeline}/{step}', [StepConfigurationController::class, 'render'])->name('steps.configuration.render');
    Route::post('/pipelines/{pipeline}/{step}', [StepConfigurationController::class, 'configure'])->name('steps.configuration.configure');

    Route::get('/pipelines/create', [PipelineController::class, 'create'])->name('pipelines.create');
    Route::get('/pipelines/{pipeline}', [PipelineController::class, 'show'])->name('pipelines.show');
    Route::get('/pipelines', [PipelineController::class, 'index'])->name('pipelines.index');
    Route::post('/pipelines', [PipelineController::class, 'store'])->name('pipelines.store');

    Route::prefix('settings')->group(function() {
        Route::get('/account', [AccountController::class, 'show'])->name('settings.account.show');
        Route::patch('/account', [AccountController::class, 'update'])->name('settings.account.update');

        Route::get('/billing', [BillingController::class, 'show'])->name('settings.billing.show');

        Route::get('/teams/{team}/memberships', [MembershipsController::class, 'store'])->name('settings.teams.memberships.store');
        Route::post('/teams/{team}/invitations', [InvitationsController::class, 'store'])->name('settings.teams.invitations.store');
        Route::delete('/teams/{team}/invitations/{invitation}', [InvitationsController::class, 'destroy'])->name('settings.teams.invitations.destroy');

        Route::get('/teams/{team}', [TeamController::class, 'show'])->name('settings.teams.show');
        Route::get('/teams', [TeamController::class, 'index'])->name('settings.teams.index');
        Route::post('/teams', [TeamController::class, 'store'])->name('settings.teams.store');
    });
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
