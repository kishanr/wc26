<?php

use App\Livewire\Dashboard;
use App\Livewire\MatchDetail;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage - Match Dashboard
Route::get('/', Dashboard::class)->name('home');

// Match Detail
Route::get('/match/{game:slug}', MatchDetail::class)->name('match.show');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', \App\Livewire\UserProfile::class)->name('profile');
    Route::get('/leagues', \App\Livewire\Leagues\LeagueIndex::class)->name('leagues.index');
    Route::get('/leagues/{league}', \App\Livewire\Leagues\LeagueDetail::class)->name('leagues.show');
    Route::get('/brackets', \App\Livewire\BracketBuilder::class)->name('brackets');
    
    // Admin
    Route::prefix('admin')->group(function () {
        Route::get('/matches', \App\Livewire\Admin\MatchManager::class)->name('admin.matches');
    });
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Placeholder routes
Route::get('/predictions', \App\Livewire\PredictionList::class)->name('predictions');
Route::get('/leaderboard', \App\Livewire\Leaderboard::class)->name('leaderboard');
