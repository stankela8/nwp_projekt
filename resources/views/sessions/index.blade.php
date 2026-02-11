<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-slate-100 leading-tight">
                    Play Sessions
                </h2>
                <p class="text-sm text-slate-400 mt-1">
                    Detailed history of all your gaming sessions across every game.
                </p>
            </div>
            <a href="{{ route('games.index') }}"
               class="text-xs text-slate-400 hover:text-cyan-400">
                ← Back to library
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Info kartice --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-slate-900/80 border border-slate-700 rounded-xl p-4">
                    <div class="text-[10px] uppercase font-black tracking-[0.2em] text-slate-500">
                        Sessions logged
                    </div>
                    <div class="mt-2 text-3xl font-bold text-cyan-300">
                        {{ $sessions->count() }}
                    </div>
                </div>

                <div class="bg-slate-900/80 border border-slate-700 rounded-xl p-4">
                    <div class="text-[10px] uppercase font-black tracking-[0.2em] text-slate-500">
                        Total time
                    </div>
                    <div class="mt-2 text-3xl font-bold text-fuchsia-300">
                        {{ number_format($sessions->sum('duration_minutes') / 60, 1) }}
                        <span class="text-lg font-normal">h</span>
                    </div>
                </div>

                <div class="bg-slate-900/80 border border-slate-700 rounded-xl p-4">
                    <div class="text-[10px] uppercase font-black tracking-[0.2em] text-slate-500">
                        Games covered
                    </div>
                    <div class="mt-2 text-3xl font-bold text-emerald-300">
                        {{ $sessions->pluck('game_id')->unique()->count() }}
                    </div>
                </div>
            </div>

            @if(session('status'))
                <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-lg text-emerald-400 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Tablica svih sesija --}}
            <div class="bg-slate-900/80 border border-slate-800 shadow-2xl sm:rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[10px] uppercase font-black tracking-widest text-slate-500 bg-slate-950/50">
                            <tr>
                                <th class="px-6 py-4">Game</th>
                                <th class="px-6 py-4">Played at</th>
                                <th class="px-6 py-4 text-center">Duration</th>
                                <th class="px-6 py-4 text-center">Mode</th>
                                <th class="px-6 py-4">Notes</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($sessions as $session)
                                <tr class="hover:bg-slate-800/20 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($session->game && $session->game->icon_url)
                                                <img src="{{ $session->game->icon_url }}"
                                                     class="h-9 w-9 rounded-lg object-cover border border-slate-700 shadow-sm">
                                            @else
                                                <div class="h-9 w-9 rounded-lg bg-slate-800 flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-700 italic">
                                                    {{ $session->game ? strtoupper(substr($session->game->title, 0, 2)) : 'NA' }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-semibold text-slate-100">
                                                    {{ $session->game->title ?? 'Unknown game' }}
                                                </div>
                                                <div class="text-[10px] uppercase font-bold text-slate-500 tracking-tighter">
                                                    Session #{{ $session->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ $session->played_at }}
                                    </td>

                                    <td class="px-6 py-4 text-center font-mono text-slate-200 font-bold">
                                        {{ $session->duration_minutes }} min
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($session->mode)
                                            <span class="px-2 py-1 rounded-full bg-slate-950 text-slate-300 text-[10px] uppercase font-black border border-slate-800">
                                                {{ $session->mode }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-600 italic">N/A</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300 max-w-xs">
                                        {{ $session->notes ?: '—' }}
                                    </td>

                                    <td class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest">
                                        <div class="flex justify-end gap-3">
                                            {{-- Ako imaš edit rutu, dodaj je ovdje --}}
                                            {{-- <a href="{{ route('play-sessions.edit', $session) }}"
                                               class="text-cyan-500 hover:text-cyan-400">Edit</a> --}}

                                            <form action="{{ route('play-sessions.destroy', $session) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete this session?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-rose-500 hover:text-rose-400">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-6 text-center text-sm text-slate-500">
                                        No sessions recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
