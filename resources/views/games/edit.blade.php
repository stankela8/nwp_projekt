<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-slate-100 leading-tight">
                    Edit Game
                </h2>
                <p class="text-sm text-slate-400 mt-1">
                    Update details, artwork and goal for this game.
                </p>
            </div>
            <a href="{{ route('games.index') }}"
               class="text-xs text-slate-400 hover:text-cyan-400">
                ← Back to library
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900/90 border border-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">
                            Game settings
                        </div>
                        <div class="mt-1 text-sm text-slate-400">
                            {{ $game->title }}
                        </div>
                    </div>
                    @if($game->icon_url)
                        <img src="{{ $game->icon_url }}"
                             class="h-10 w-10 rounded-lg object-cover border border-slate-700 shadow-md">
                    @endif
                </div>

                <form method="POST" action="{{ route('games.update', $game) }}" class="px-6 py-6 space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                            Title
                        </label>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $game->title) }}"
                            class="block w-full bg-slate-950 border border-slate-700 rounded-lg text-sm text-slate-100 px-3 py-2 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                            required
                        >
                        @error('title')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Icon URL --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                            Icon URL (optional)
                        </label>
                        <input
                            type="url"
                            name="icon_url"
                            value="{{ old('icon_url', $game->icon_url) }}"
                            class="block w-full bg-slate-950 border border-slate-700 rounded-lg text-sm text-slate-100 px-3 py-2 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                            placeholder="https://..."
                        >
                        @error('icon_url')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Platform --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                            Platform
                        </label>
                        <input
                            type="text"
                            name="platform"
                            value="{{ old('platform', $game->platform) }}"
                            class="block w-full bg-slate-950 border border-slate-700 rounded-lg text-sm text-slate-100 px-3 py-2 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                            placeholder="PC, PS5, Mobile..."
                        >
                        @error('platform')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Genre --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                            Genre
                        </label>
                        <input
                            type="text"
                            name="genre"
                            value="{{ old('genre', $game->genre) }}"
                            class="block w-full bg-slate-950 border border-slate-700 rounded-lg text-sm text-slate-100 px-3 py-2 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                            placeholder="MOBA, FPS, RPG..."
                        >
                        @error('genre')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Rank --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                            Rank
                        </label>
                        <input
                            type="text"
                            name="rank"
                            value="{{ old('rank', $game->rank) }}"
                            class="block w-full bg-slate-950 border border-slate-700 rounded-lg text-sm text-slate-100 px-3 py-2 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                            placeholder="Gold, Silver 3..."
                        >
                        @error('rank')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Goal (hours) --}}
                    <div class="pt-2 border-t border-slate-800 mt-4">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-1">
                            Goal (total hours to play)
                        </label>
                        <input
                            type="number"
                            step="0.1"
                            min="0"
                            name="goal_hours"
                            value="{{ old('goal_hours', optional($game->gameHoursGoal)->target_minutes ? optional($game->gameHoursGoal)->target_minutes / 60 : '') }}"
                            class="block w-full bg-slate-950 border border-slate-700 rounded-lg text-sm text-slate-100 px-3 py-2 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                            placeholder="e.g. 100"
                        >
                        <p class="mt-1 text-[11px] text-slate-500">
                            Set how many hours you want to reach in total for this game. This drives the goal progress bar on your library.
                        </p>
                        @error('goal_hours')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="pt-4">
    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-1">
        Rank goal (optional)
    </label>
    <input
        type="text"
        name="goal_rank"
        value="{{ old('goal_rank', optional($game->gameHoursGoal)->target_rank ?? '') }}"
        class="block w-full bg-slate-950 border border-slate-700 rounded-lg text-sm text-slate-100 px-3 py-2 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
        placeholder="e.g. Gold, Silver 1"
    >
    <p class="mt-1 text-[11px] text-slate-500">
        Set desired rank for this game. You can update it as you progress.
    </p>
</div>


                    <div class="pt-4 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 rounded-lg bg-gradient-to-r from-fuchsia-600 to-cyan-600 text-white text-xs font-black uppercase tracking-widest shadow-lg hover:scale-105 transition-all">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
