<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlaySessionController;
use App\Models\Game;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('games.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// JSON stats za Node.js (javno, bez auth-a)
Route::get('/stats/raw', function () {
    $games = Game::withSum('playSessions as total_minutes', 'duration_minutes')
        ->get(['id', 'title', 'genre']);

    $games = $games->map(function ($game) {
        return [
            'id' => $game->id,
            'name' => $game->title,
            'genre' => $game->genre,
            'total_minutes' => $game->total_minutes ?? 0,
        ];
    });

    return response()->json([
        'games' => $games,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('games', GameController::class);
    Route::resource('play-sessions', PlaySessionController::class);

    Route::post('/games/{game}/sessions', [PlaySessionController::class, 'storeForGame'])
        ->name('games.sessions.store');

    Route::get('/games/{game}/sessions', [PlaySessionController::class, 'indexForGame'])
        ->name('games.sessions.index');
});

require __DIR__.'/auth.php';
