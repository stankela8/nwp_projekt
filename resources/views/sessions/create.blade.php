<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Play Session
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('play-sessions.store') }}">
                        @csrf

                        <div class="mt-4">
                            <label>Game</label>
                            <select name="game_id" class="border rounded w-full" required>
                                <option value="">-- select game --</option>
                                @foreach($games as $game)
                                    <option value="{{ $game->id }}">{{ $game->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <label>Played at</label>
                            <input type="datetime-local" name="played_at" class="border rounded w-full" required>
                        </div>

                        <div class="mt-4">
                            <label>Duration (minutes)</label>
                            <input type="number" name="duration_minutes" class="border rounded w-full" min="1" required>
                        </div>

                        <div class="mt-4">
                            <label>Mode</label>
                            <input type="text" name="mode" class="border rounded w-full">
                        </div>

                        <div class="mt-4">
                            <label>Notes</label>
                            <textarea name="notes" class="border rounded w-full"></textarea>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
