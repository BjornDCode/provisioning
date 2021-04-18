<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;

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

require __DIR__.'/auth.php';
