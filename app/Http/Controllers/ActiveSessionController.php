<?php

namespace App\Http\Controllers;

use App\Models\ActiveSession;
use App\Models\Game;
use App\Models\PlaySession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActiveSessionController extends Controller
{
    public function start(Game $game)
    {
        $user = Auth::user();

        // Ako već postoji aktivna sesija za ovu igru/usera, samo je vrati
        $active = ActiveSession::firstOrCreate(
            [
                'user_id' => $user->id,
                'game_id' => $game->id,
            ],
            [
                'started_at' => now(),
            ]
        );

        return redirect()->route('games.index')
            ->with('status', 'Session started for ' . $game->title);
    }

    public function stop(Game $game, Request $request)
    {
        $user = Auth::user();

        $active = ActiveSession::where('user_id', $user->id)
            ->where('game_id', $game->id)
            ->first();

        if (! $active) {
            return redirect()->route('games.index')
                ->with('status', 'No active session for this game.');
        }

        $startedAt = $active->started_at;
        $endedAt   = now();
        $minutes   = max(1, $startedAt->diffInMinutes($endedAt));

        // Kreiraj normalnu PlaySession
        PlaySession::create([
            'user_id'          => $user->id,
            'game_id'          => $game->id,
            'played_at'        => $startedAt,
            'duration_minutes' => $minutes,
            'mode'             => 'Live timer',
            'notes'            => null,
        ]);

        $active->delete();

        return redirect()->route('games.index')
            ->with('status', 'Session recorded: ' . $minutes . ' min for ' . $game->title);
    }
}
