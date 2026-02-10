<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PlaySession;
use Carbon\Carbon;


class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
{
    $games = Auth::user()
        ->games()
        ->withSum('playSessions as total_minutes', 'duration_minutes')
        ->with(['playSessions' => function ($q) {
            $q->orderByDesc('played_at')->limit(1);
        }])
        ->orderBy('title')
        ->get();

    $totalGames = $games->count();

    $totalMinutes = $games->sum(function ($game) {
        return $game->total_minutes ?? 0;
    });

    $topGame = $games->sortByDesc(function ($game) {
        return $game->total_minutes ?? 0;
    })->first();

    $from = Carbon::now()->subDays(30);

$recentSessions = PlaySession::where('user_id', Auth::id())
    ->where('played_at', '>=', $from)
    ->with('game')
    ->get();

$recentMinutes = $recentSessions->sum('duration_minutes');
$recentCount = $recentSessions->count();

$topRecentGame = $recentSessions
    ->groupBy('game_id')
    ->map(function ($sessions) {
        return [
            'game' => $sessions->first()->game,
            'minutes' => $sessions->sum('duration_minutes'),
        ];
    })
    ->sortByDesc('minutes')
    ->first();

return view('games.index', [
    'games' => $games,
    'totalGames' => $totalGames,
    'totalMinutes' => $totalMinutes,
    'topGame' => $topGame,
    'recentMinutes' => $recentMinutes,
    'recentCount' => $recentCount,
    'topRecentGame' => $topRecentGame,
]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('games.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'platform' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'rank' => 'nullable|string|max:255',
            'icon_url' => 'nullable|url|max:255',

        ]);

        Auth::user()->games()->create($validated);

        return redirect()->route('games.index')->with('status', 'Game added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        $this->authorizeGame($game);
        return view('games.edit', compact('game'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        $this->authorizeGame($game);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'platform' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'rank' => 'nullable|string|max:255',
            'icon_url' => 'nullable|url|max:255',

        ]);

        $game->update($validated);

        return redirect()->route('games.index')->with('status', 'Game updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
       public function destroy(Game $game)
    {
        $this->authorizeGame($game);
        $game->delete();

        return redirect()->route('games.index')->with('status', 'Game deleted!');
    }

    protected function authorizeGame(Game $game)
    {
        if ($game->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
