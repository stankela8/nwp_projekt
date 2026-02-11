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

    <div class="py-12 relative">
        {{-- GLAVNI KONTEJNER --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- GORNJE 3 KARTICE --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:pr-80">
                <div class="bg-slate-900/80 border border-slate-700 rounded-xl p-4 shadow-sm">
                    <div class="text-sm text-slate-400 font-medium uppercase tracking-wider text-[10px]">
                        Games tracked
                    </div>
                    <div class="mt-2 text-3xl font-bold text-cyan-300">{{ $totalGames }}</div>
                </div>

                <div class="bg-slate-900/80 border border-slate-700 rounded-xl p-4 shadow-sm">
                    <div class="text-sm text-slate-400 font-medium uppercase tracking-wider text-[10px]">
                        Total time played
                    </div>
                    <div class="mt-2 text-3xl font-bold text-fuchsia-300">
                        {{ number_format($totalMinutes / 60, 1) }}
                        <span class="text-lg font-normal">h</span>
                    </div>
                </div>

                <div class="bg-slate-900/80 border border-slate-700 rounded-xl p-4 shadow-sm">
                    <div class="text-sm text-slate-400 font-medium uppercase tracking-wider text-[10px]">
                        Most played game
                    </div>
                    @if($topGame && ($topGame->total_minutes ?? 0) > 0)
                        <div class="mt-2 text-lg font-bold text-slate-100 truncate">
                            {{ $topGame->title }}
                        </div>
                        <div class="text-sm text-slate-400">
                            {{ number_format(($topGame->total_minutes ?? 0) / 60, 1) }} h total
                        </div>
                    @else
                        <div class="mt-2 text-lg text-slate-500 italic font-medium">No data</div>
                    @endif
                </div>
            </div>

            {{-- TABLICA SEKCIJA --}}
            <div class="space-y-4 lg:pr-80">
                <div class="flex items-center justify-between">
                    <a href="{{ route('games.create') }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg bg-gradient-to-r from-fuchsia-600 to-cyan-600 text-white text-xs font-black uppercase tracking-widest shadow-lg hover:scale-105 transition-all">
                        + Add New Game
                    </a>
                </div>

                @if(session('status'))
                    <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-lg text-emerald-400 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="bg-slate-900/80 border border-slate-800 overflow-hidden shadow-2xl sm:rounded-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-[10px] uppercase font-black tracking-widest text-slate-500 bg-slate-950/50">
                                <tr>
                                    <th class="px-6 py-4">Game</th>
                                    <th class="px-6 py-4 text-center">Rank</th>
                                    <th class="px-6 py-4 text-center">Total (h)</th>
                                    <th class="px-6 py-4">Last session</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/50">
                                @foreach($games as $game)
                                    @php $last = $game->playSessions->first(); @endphp
                                    <tr class="hover:bg-slate-800/20 transition-colors group">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-4">
                                                @if($game->icon_url)
                                                    <img src="{{ $game->icon_url }}"
                                                         class="h-12 w-12 rounded-lg object-cover border border-slate-700 shadow-md group-hover:border-cyan-500/50 transition-colors">
                                                @else
                                                    <div class="h-12 w-12 rounded-lg bg-slate-800 flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-700 italic">
                                                        {{ strtoupper(substr($game->title, 0, 2)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-bold text-slate-100 text-base leading-tight">
                                                        {{ $game->title }}
                                                    </div>
                                                    <div class="text-[10px] uppercase font-bold text-slate-500 tracking-tighter">
                                                        {{ $game->platform }}
                                                        <span class="mx-1 text-slate-700">•</span>
                                                        {{ $game->genre }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-center">
    <span class="px-2 py-1 rounded bg-slate-950 text-slate-400 text-[10px] uppercase font-black border border-slate-800">
        {{ $game->rank ?? 'unranked' }}
    </span>

    @if($game->rank_goal)
        <div class="mt-1 text-[10px] text-cyan-400 font-bold uppercase">
            Target: {{ $game->rank_goal->target_rank }}
        </div>
    @endif
</td>

                                        <td class="px-6 py-5 text-center font-mono text-slate-200 font-bold">
                                            {{ number_format(($game->total_minutes ?? 0) / 60, 1) }}
                                        </td>
                                        <td class="px-6 py-5">
                                            @if($last)
                                                <div class="text-slate-300 text-xs font-medium">
                                                    {{ $last->played_at }}
                                                </div>
                                                <div class="text-slate-500 text-[10px] font-bold uppercase">
                                                    {{ $last->duration_minutes }} min
                                                    <span class="text-slate-700 mx-1">/</span>
                                                    {{ $last->mode }}
                                                </div>
                                            @else
                                                <span class="text-slate-600 text-xs italic">No activity</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-5 text-right text-[10px] font-black uppercase tracking-widest">
                                            <div class="flex flex-col items-end gap-2">

                                                {{-- Start / Stop + timer --}}
                                                <div class="flex items-center gap-3">
                                                    @if($game->activeSession)
                                                        <form action="{{ route('games.sessions.stop', $game) }}" method="POST">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-3 py-1 rounded-full bg-red-600/20 text-red-400 border border-red-500/40 text-[10px] font-black uppercase hover:bg-red-600 hover:text-white transition-all">
                                                                Stop session
                                                            </button>
                                                        </form>
                                                        <span class="flex items-center gap-1 text-[10px] font-mono text-amber-300"
                                                              data-started-at="{{ $game->activeSession->started_at->timestamp }}"
                                                              data-game-id="{{ $game->id }}"
                                                              id="live-timer-{{ $game->id }}">
                                                            <span class="inline-block h-2 w-2 rounded-full bg-amber-400 animate-pulse"></span>
                                                            <span class="timer-text">00:00:00</span>
                                                        </span>
                                                    @else
                                                        <form action="{{ route('games.sessions.start', $game) }}" method="POST">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-3 py-1 rounded-full bg-emerald-600/20 text-emerald-400 border border-emerald-500/40 text-[10px] font-black uppercase hover:bg-emerald-600 hover:text-white transition-all">
                                                                Start session
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>

                                                {{-- Postojeći Edit/Delete --}}
                                                <div class="flex justify-end gap-4">
                                                    <a href="{{ route('games.edit', $game) }}"
                                                       class="text-cyan-500 hover:text-cyan-400">Edit</a>
                                                    <form action="{{ route('games.destroy', $game) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-fuchsia-500 hover:text-fuchsia-400"
                                                                onclick="return confirm('Delete game?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- QUICK ADD FORM --}}
                                    <tr class="bg-slate-950/30">
                                        <td colspan="5" class="px-6 py-3 border-t border-slate-800/30">
                                            <form method="POST"
                                                  action="{{ route('games.sessions.store', $game) }}"
                                                  class="flex flex-wrap items-center gap-3">
                                                @csrf
                                                <span class="text-[9px] font-black text-slate-600 uppercase tracking-[0.2em]">
                                                    Quick Add:
                                                </span>
                                                <input type="datetime-local" name="played_at"
                                                       class="bg-slate-900 border-slate-800 rounded text-[11px] py-1 text-slate-300 focus:ring-1 focus:ring-cyan-500"
                                                       required>
                                                <input type="number" name="duration_minutes" placeholder="Min"
                                                       class="bg-slate-900 border-slate-800 rounded text-[11px] py-1 w-16 text-slate-300"
                                                       required>
                                                <input type="text" name="mode" placeholder="Mode"
                                                       class="bg-slate-900 border-slate-800 rounded text-[11px] py-1 w-24 text-slate-300">
                                                <input type="text" name="notes" placeholder="Notes..."
                                                       class="bg-slate-900 border-slate-800 rounded text-[11px] py-1 flex-1 text-slate-300">
                                                <button type="submit"
                                                        class="bg-cyan-600/20 text-cyan-400 border border-cyan-500/30 px-3 py-1 rounded text-[10px] font-black uppercase hover:bg-cyan-500 hover:text-white transition-all">
                                                    Add
                                                </button>
                                                <a href="{{ route('games.sessions.index', $game) }}"
                                                   class="text-[10px] font-bold text-slate-600 hover:text-cyan-500 ml-auto uppercase tracking-tighter">
                                                    History
                                                </a>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- GOAL PROGRESS (ako postoji cilj) --}}
                                    @if($game->goal)
                                        <tr class="bg-slate-950/40">
                                            <td colspan="5" class="px-6 py-3 border-t border-slate-800/30">
                                                <div class="flex items-center gap-3">
                                                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-[0.2em]">
                                                        Goal:
                                                    </span>
                                                    <span class="text-[11px] text-slate-300">
                                                        Play {{ number_format(($game->goal->target_minutes ?? 0) / 60, 1) }} h total
                                                    </span>
                                                </div>
                                                <div class="mt-2 w-full h-2 rounded-full bg-slate-900 overflow-hidden">
                                                    <div class="h-2 rounded-full bg-gradient-to-r from-emerald-500 to-cyan-500"
                                                         style="width: {{ $game->goal_progress }}%;">
                                                    </div>
                                                </div>
                                                <div class="mt-1 flex justify-between text-[10px] text-slate-500">
                                                    <span>{{ number_format(($game->total_minutes ?? 0) / 60, 1) }} h so far</span>
                                                    <span>{{ $game->goal_progress }} %</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- NODE.JS GRAFOVI --}}
            <div class="mt-8 space-y-6 lg:pr-80">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4">
                        <h2 class="text-sm font-semibold text-slate-100 mb-4">
                            Time played by genre (Node.js)
                        </h2>
                        <canvas id="genreChart" height="200"></canvas>
                    </div>

                    <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4">
                        <h2 class="text-sm font-semibold text-slate-100 mb-4">
                            Top 5 games by playtime (Node.js)
                        </h2>
                        <canvas id="topGamesChart" height="200"></canvas>
                    </div>
                </div>
            </div>

        </div>

        {{-- DESNA KARTICA (LAST 30 DAYS) --}}
        <div class="lg:absolute lg:right-8 lg:top-12 w-full lg:w-64 px-4 sm:px-6 lg:px-0 mt-8 lg:mt-0">
            <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-2xl backdrop-blur-sm">
                <div class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-slate-800 pb-3 mb-4 flex items-center justify-between">
                    Last 30 days
                    <span class="h-1.5 w-1.5 rounded-full bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.5)]"></span>
                </div>
                <div class="space-y-5">
                    <div>
                        <div class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Total time</div>
                        <div class="text-2xl font-bold text-cyan-400">
                            {{ number_format($recentMinutes / 60, 1) }}
                            <span class="text-sm font-medium opacity-50">h</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Sessions</div>
                        <div class="text-xl font-bold text-fuchsia-400">{{ $recentCount }}</div>
                    </div>
                    <div class="pt-2">
                        <div class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Top performance</div>
                        @if($topRecentGame && $topRecentGame['game'])
                            <div class="text-sm font-bold text-slate-200 mt-1 truncate">
                                {{ $topRecentGame['game']->title }}
                            </div>
                            <div class="text-[10px] font-bold text-cyan-500/70">
                                {{ number_format($topRecentGame['minutes'] / 60, 1) }} h played
                            </div>
                        @else
                            <div class="text-xs text-slate-600 italic mt-1">No recent activity</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/app.js')

    {{-- Live timer --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const timerEls = document.querySelectorAll('[id^="live-timer-"]');
        if (!timerEls.length) return;

        function pad(n) {
            return n.toString().padStart(2, '0');
        }

        function updateTimers() {
            const now = Math.floor(Date.now() / 1000);

            timerEls.forEach(el => {
                const startedAt = Number(el.dataset.startedAt);
                const diff = Math.max(0, now - startedAt);

                const hours = Math.floor(diff / 3600);
                const minutes = Math.floor((diff % 3600) / 60);
                const seconds = diff % 60;

                const textEl = el.querySelector('.timer-text');
                if (textEl) {
                    textEl.textContent = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
                }
            });
        }

        updateTimers();
        setInterval(updateTimers, 1000);
    });
    </script>

    {{-- Node.js grafovi --}}
    <script>
    document.addEventListener('DOMContentLoaded', async () => {
        const genreCtx = document.getElementById('genreChart');
        const topGamesCtx = document.getElementById('topGamesChart');

        if (genreCtx) {
            try {
                const res = await fetch('http://localhost:3001/api/stats/genres');
                const data = await res.json();

                new window.Chart(genreCtx, {
                    type: 'pie',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.values,
                            backgroundColor: [
                                '#22c55e', '#3b82f6', '#f97316',
                                '#e11d48', '#8b5cf6', '#14b8a6'
                            ],
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                labels: { color: '#cbd5f5' }
                            }
                        }
                    }
                });
            } catch (e) {
                console.error('Genre chart error', e);
            }
        }

        if (topGamesCtx) {
            try {
                const res = await fetch('http://localhost:3001/api/stats/top-games');
                const data = await res.json();

                new window.Chart(topGamesCtx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Minutes played',
                            data: data.values,
                            backgroundColor: '#3b82f6'
                        }]
                    },
                    options: {
                        scales: {
                            x: { ticks: { color: '#cbd5f5' } },
                            y: { ticks: { color: '#cbd5f5' } }
                        },
                        plugins: {
                            legend: {
                                labels: { color: '#cbd5f5' }
                            }
                        }
                    }
                });
            } catch (e) {
                console.error('Top games chart error', e);
            }
        }
    });
    </script>
</x-app-layout>
