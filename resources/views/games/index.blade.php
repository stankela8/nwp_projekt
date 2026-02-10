<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-slate-100 leading-tight">
                    Game Library
                </h2>
                <p class="text-sm text-slate-400 mt-1">
                    Track your playtime, ranks and grind across all your games.
                </p>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-xs text-slate-400">
                <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Tracking active
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 3 SUMMARY CARDS U RAVNINI --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-slate-900/80 border border-slate-700 rounded-xl p-4">
                    <div class="text-sm text-slate-400">Games tracked</div>
                    <div class="mt-2 text-2xl font-semibold text-cyan-300">
                        {{ $totalGames }}
                    </div>
                </div>

                <div class="bg-slate-900/80 border border-slate-700 rounded-xl p-4">
                    <div class="text-sm text-slate-400">Total time played</div>
                    <div class="mt-2 text-2xl font-semibold text-fuchsia-300">
                        {{ number_format($totalMinutes / 60, 1) }} h
                    </div>
                </div>

                <div class="bg-slate-900/80 border border-slate-700 rounded-xl p-4">
                    <div class="text-sm text-slate-400">Most played game</div>
                    @if($topGame && ($topGame->total_minutes ?? 0) > 0)
                        <div class="mt-2 text-lg font-semibold text-slate-100">
                            {{ $topGame->title }}
                        </div>
                        <div class="text-sm text-slate-400">
                            {{ number_format(($topGame->total_minutes ?? 0) / 60, 1) }} h
                        </div>
                    @else
                        <div class="mt-2 text-lg text-slate-500">
                            No playtime yet
                        </div>
                    @endif
                </div>
            </div>

            {{-- TABLICA + LAST 30 DAYS U ISTOJ RAVNINI --}}
            <div class="mt-4 flex flex-col lg:flex-row gap-4">

                {{-- LEFT: tablica (široko) --}}
                <div class="flex-1">
                    <a href="{{ route('games.create') }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg bg-gradient-to-r from-fuchsia-500 to-cyan-500 text-slate-900 text-sm font-semibold shadow hover:from-fuchsia-400 hover:to-cyan-400">
                        + Add Game
                    </a>

                    @if(session('status'))
                        <div class="mt-4 text-emerald-400 text-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mt-4 bg-slate-900/80 border border-slate-800 overflow-hidden shadow-sm sm:rounded-xl">
                        <div class="p-6 text-slate-100">
                            @if($games->isEmpty())
                                <p class="text-slate-400">No games yet.</p>
                            @else
                                <table class="table-auto w-full text-sm">
                                    <thead class="text-xs uppercase text-slate-400">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Game</th>
                                        <th class="px-4 py-2 text-left">Rank</th>
                                        <th class="px-4 py-2 text-left">Total time (h)</th>
                                        <th class="px-4 py-2 text-left">Last session</th>
                                        <th class="px-4 py-2 text-left">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($games as $game)
                                        @php
                                            $last = $game->playSessions->first();
                                        @endphp

                                        <tr class="bg-slate-900/60">
                                            {{-- GAME CELL WITH ICON + META --}}
                                            <td class="border border-slate-800 px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    @if($game->icon_url)
                                                        <img src="{{ $game->icon_url }}" alt="{{ $game->title }} icon"
                                                             class="h-12 w-12 rounded-md object-cover border border-slate-700">
                                                    @else
                                                        <div class="h-12 w-12 rounded-md bg-slate-800 flex items-center justify-center text-xs text-slate-400">
                                                            {{ strtoupper(substr($game->title, 0, 2)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="font-semibold text-slate-100 text-sm">
                                                            {{ $game->title }}
                                                        </div>
                                                        <div class="text-xs text-slate-400">
                                                            {{ $game->platform }} • {{ $game->genre }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="border border-slate-800 px-4 py-2">
                                                {{ $game->rank }}
                                            </td>

                                            <td class="border border-slate-800 px-4 py-2">
                                                {{ number_format(($game->total_minutes ?? 0) / 60, 1) }}
                                            </td>

                                            <td class="border border-slate-800 px-4 py-2">
                                                @if($last)
                                                    <div>{{ $last->played_at }}</div>
                                                    <div class="text-slate-400 text-xs">
                                                        {{ $last->duration_minutes }} min, {{ $last->mode }}
                                                    </div>
                                                @else
                                                    <div class="text-slate-500 text-xs">No sessions yet</div>
                                                @endif
                                            </td>

                                            <td class="border border-slate-800 px-4 py-2">
                                                <a href="{{ route('games.edit', $game) }}"
                                                   class="text-xs text-cyan-300 hover:text-cyan-200">
                                                    Edit
                                                </a>
                                                <form action="{{ route('games.destroy', $game) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-xs text-fuchsia-300 hover:text-fuchsia-200 ml-2"
                                                            onclick="return confirm('Delete this game?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        {{-- QUICK ADD SESSION ROW --}}
                                        <tr class="bg-slate-900/40">
                                            <td colspan="5" class="border border-slate-800 px-4 py-3">
                                                <form method="POST" action="{{ route('games.sessions.store', $game) }}"
                                                      class="flex flex-wrap items-end gap-4">
                                                    @csrf

                                                    <div>
                                                        <label class="block text-xs text-slate-400">Played at</label>
                                                        <input type="datetime-local" name="played_at"
                                                               class="border border-slate-700 bg-slate-950/80 rounded text-xs px-2 py-1 text-slate-100"
                                                               required>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs text-slate-400">Duration (min)</label>
                                                        <input type="number" name="duration_minutes"
                                                               class="border border-slate-700 bg-slate-950/80 rounded text-xs px-2 py-1 w-24 text-slate-100"
                                                               min="1" required>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs text-slate-400">Mode</label>
                                                        <input type="text" name="mode"
                                                               class="border border-slate-700 bg-slate-950/80 rounded text-xs px-2 py-1 w-32 text-slate-100">
                                                    </div>

                                                    <div class="flex-1 min-w-[150px]">
                                                        <label class="block text-xs text-slate-400">Notes</label>
                                                        <input type="text" name="notes"
                                                               class="border border-slate-700 bg-slate-950/80 rounded text-xs px-2 py-1 w-full text-slate-100">
                                                    </div>

                                                    <div>
                                                        <button type="submit"
                                                                class="px-3 py-1 text-xs font-semibold rounded-full bg-cyan-500/80 text-slate-900 hover:bg-cyan-400">
                                                            Add session
                                                        </button>
                                                    </div>
                                                </form>

                                                <div class="mt-2">
                                                    <a href="{{ route('games.sessions.index', $game) }}"
                                                       class="text-xs text-slate-400 hover:text-cyan-300">
                                                        View all sessions
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Last 30 days summary, poravnan uz desni rub containera --}}
                <div class="w-full lg:w-72 bg-slate-900/80 border border-slate-800 rounded-xl p-4 h-fit">
                    <div class="text-sm text-slate-400">Last 30 days</div>

                    <div class="mt-3">
                        <div class="text-xs text-slate-400">Total time</div>
                        <div class="text-xl font-semibold text-cyan-300">
                            {{ number_format($recentMinutes / 60, 1) }} h
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-xs text-slate-400">Sessions</div>
                        <div class="text-lg font-semibold text-fuchsia-300">
                            {{ $recentCount }}
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-xs text-slate-400">Top game</div>
                        @if($topRecentGame && $topRecentGame['game'])
                            <div class="mt-1 text-sm font-semibold text-slate-100">
                                {{ $topRecentGame['game']->title }}
                            </div>
                            <div class="text-xs text-slate-400">
                                {{ number_format($topRecentGame['minutes'] / 60, 1) }} h in last 30 days
                            </div>
                        @else
                            <div class="mt-1 text-xs text-slate-500">
                                No sessions in last 30 days
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
