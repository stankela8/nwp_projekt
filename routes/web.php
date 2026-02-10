<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlaySessionController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('games.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('games', GameController::class)->middleware(['auth']);
    Route::resource('play-sessions', PlaySessionController::class)->middleware(['auth']);
    Route::post('/games/{game}/sessions', [PlaySessionController::class, 'storeForGame'])
    ->name('games.sessions.store')
    ->middleware(['auth']);
});
    Route::get('/games/{game}/sessions', [PlaySessionController::class, 'indexForGame'])
    ->name('games.sessions.index')
    ->middleware(['auth']);


require __DIR__.'/auth.php';
