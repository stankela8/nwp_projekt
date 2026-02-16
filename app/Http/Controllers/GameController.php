<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Goal;
use App\Models\PlaySession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $user = Auth::user();

    // sort: 'name' (default) ili 'time'
    $sort = $request->get('sort', 'name');

    $games = $user->games()
        ->withSum('playSessions as total_minutes', 'duration_minutes')
        ->with([
            // zadnja sesija za prikaz "Last session"
            'playSessions' => function ($q) {
                $q->orderByDesc('played_at')->limit(1);
            },
            // aktivna live sesija za ovog usera
            'activeSession' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            },
        ]);

    // primijeni sortiranje
    if ($sort === 'time') {
        $games->orderByDesc('total_minutes');   // najviše sati prvo
    } else {
        $games->orderBy('title');               // abecedno
    }

    $games = $games->get();

    $totalGames = $games->count();

    $totalMinutes = $games->sum(function ($game) {
        return $game->total_minutes ?? 0;
    });

    $topGame = $games
        ->sortByDesc(function ($game) {
            return $game->total_minutes ?? 0;
        })
        ->first();

    $from = Carbon::now()->subDays(30);

    $recentSessions = PlaySession::where('user_id', $user->id)
        ->where('played_at', '>=', $from)
        ->with('game')
        ->get();

    $recentMinutes = $recentSessions->sum('duration_minutes');
    $recentCount   = $recentSessions->count();

    $topRecentGame = $recentSessions
        ->groupBy('game_id')
        ->map(function ($sessions) {
            return [
                'game'    => $sessions->first()->game,
                'minutes' => $sessions->sum('duration_minutes'),
            ];
        })
        ->sortByDesc('minutes')
        ->first();

    // goals po igri (hours + rank)
    $hoursGoalMap = Goal::where('user_id', $user->id)
        ->where('type', 'game_hours')
        ->get()
        ->groupBy('game_id');

    $rankGoalMap = Goal::where('user_id', $user->id)
        ->where('type', 'rank')
        ->get()
        ->groupBy('game_id');

    $games->each(function ($game) use ($hoursGoalMap, $rankGoalMap) {
        // hours goal
        $gameGoal = optional($hoursGoalMap->get($game->id))->first();
        if ($gameGoal) {
            $target  = $gameGoal->target_minutes ?? 0;
            $current = $game->total_minutes ?? 0;

            $progress = $target > 0
                ? min(100, round($current / $target * 100))
                : 0;

            $game->goal          = $gameGoal;
            $game->goal_progress = $progress;
        } else {
            $game->goal          = null;
            $game->goal_progress = null;
        }

        // rank goal
        $rankGoal = optional($rankGoalMap->get($game->id))->first();
        $game->rank_goal = $rankGoal;
    });

    return view('games.index', [
        'games'         => $games,
        'totalGames'    => $totalGames,
        'totalMinutes'  => $totalMinutes,
        'topGame'       => $topGame,
        'recentMinutes' => $recentMinutes,
        'recentCount'   => $recentCount,
        'topRecentGame' => $topRecentGame,
        'sort'          => $sort,
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
            'title'    => 'required|string|max:255',
            'platform' => 'nullable|string|max:255',
            'genre'    => 'nullable|string|max:255',
            'rank'     => 'nullable|string|max:255',
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

        // eager-loadaj goals ako želiš
        $game->load(['gameHoursGoal', 'rankGoal']);

        return view('games.edit', compact('game'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        $this->authorizeGame($game);

        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'platform'   => 'nullable|string|max:255',
            'genre'      => 'nullable|string|max:255',
            'rank'       => 'nullable|string|max:255',
            'icon_url'   => 'nullable|url|max:255',
            'goal_hours' => 'nullable|numeric|min:0',
            'goal_rank'  => 'nullable|string|max:255',
        ]);

        $game->update($validated);

        // update / create game_hours goal
        if ($request->filled('goal_hours')) {
            $targetMinutes = (int)($request->input('goal_hours') * 60);

            $game->goals()->updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'type'    => 'game_hours',
                ],
                [
                    'target_minutes' => $targetMinutes,
                ]
            );
        }

        // update / create rank goal
        if ($request->filled('goal_rank')) {
            $game->goals()->updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'type'    => 'rank',
                ],
                [
                    'target_rank' => $request->input('goal_rank'),
                ]
            );
        } else {
            // ako je polje prazno, izbriši rank goal
            $game->goals()
                ->where('user_id', Auth::id())
                ->where('type', 'rank')
                ->delete();
        }

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
