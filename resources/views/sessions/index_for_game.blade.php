<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-slate-100 leading-tight">
                    Sessions – {{ $game->title }}
                </h2>
                <p class="text-sm text-slate-400 mt-1">
                    Full history of your play sessions for this game.
                </p>
            </div>
            <a href="{{ route('games.index') }}"
               class="text-xs text-slate-400 hover:text-cyan-400">
                ← Back to library
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Info kartice za ovu igru --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-slate-900/80 border border-slate-700 rounded-xl p-4">
                    <div class="text-[10px] uppercase font-black tracking-[0.2em] text-slate-500">
                        Sessions
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
                        Average session
                    </div>
                    <div class="mt-2 text-3xl font-bold text-emerald-300">
                        @php
                            $count = max(1, $sessions->count());
                            $avg = $sessions->sum('duration_minutes') / $count;
                        @endphp
                        {{ number_format($avg, 1) }}
                        <span class="text-lg font-normal">min</span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900/80 border border-slate-800 shadow-2xl sm:rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">
                        Session history
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ $sessions->count() ? 'Latest first' : 'No sessions yet' }}
                    </div>
                </div>

                <div class="overflow-x-auto">
                    @if($sessions->isEmpty())
                        <div class="px-6 py-6 text-sm text-slate-500">
                            No sessions for this game yet.
                        </div>
                    @else
                        <table class="w-full text-sm text-left">
                            <thead class="text-[10px] uppercase font-black tracking-widest text-slate-500 bg-slate-950/50">
                                <tr>
                                    <th class="px-6 py-4">Played at</th>
                                    <th class="px-6 py-4 text-center">Duration</th>
                                    <th class="px-6 py-4 text-center">Mode</th>
                                    <th class="px-6 py-4">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/50">
                                @foreach($sessions as $session)
                                    <tr class="hover:bg-slate-800/20 transition-colors">
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
                                        <td class="px-6 py-4 text-sm text-slate-300 max-w-xl">
                                            {{ $session->notes ?: '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
