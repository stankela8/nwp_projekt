<?php

namespace App\Http\Controllers;

use App\Models\PlaySession;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaySessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = Auth::user()
            ->playSessions()
            ->with('game')
            ->orderByDesc('played_at')
            ->get();

        return view('sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $games = Auth::user()->games()->orderBy('title')->get();

        return view('sessions.create', compact('games'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'played_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'mode' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        PlaySession::create($validated);

        return redirect()->route('play-sessions.index')->with('status', 'Session added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlaySession $play_session)
    {
         $this->authorizeSession($play_session);

        $games = Auth::user()->games()->orderBy('title')->get();

        return view('sessions.edit', [
            'session' => $play_session,
            'games' => $games,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlaySession $play_session)
    {
        $this->authorizeSession($play_session);

        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'played_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'mode' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $play_session->update($validated);

        return redirect()->route('play-sessions.index')->with('status', 'Session updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlaySession $play_session)
    {
        $this->authorizeSession($play_session);

        $play_session->delete();

        return redirect()->route('play-sessions.index')->with('status', 'Session deleted!');
    }

    protected function authorizeSession(PlaySession $session)
    {
        if ($session->user_id !== Auth::id()) {
            abort(403);
        }
    }

    public function storeForGame(Request $request, Game $game)
{
    $this->authorizeGame($game);

    $validated = $request->validate([
        'played_at' => 'required|date',
        'duration_minutes' => 'required|integer|min:1',
        'mode' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ]);

    $validated['user_id'] = Auth::id();
    $validated['game_id'] = $game->id;

    PlaySession::create($validated);

    return redirect()->route('games.index')->with('status', 'Session added!');
}

protected function authorizeGame(Game $game)
{
    if ($game->user_id !== Auth::id()) {
        abort(403);
    }
}

public function indexForGame(Game $game)
{
    $this->authorizeGame($game);

    $sessions = $game->playSessions()
        ->where('user_id', Auth::id())
        ->orderByDesc('played_at')
        ->get();

    return view('sessions.index_for_game', compact('game', 'sessions'));
}


}
