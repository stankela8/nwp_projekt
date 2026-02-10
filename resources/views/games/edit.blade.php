<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Game
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('games.update', $game) }}">
                        @csrf
                        @method('PUT')

                        <div class="mt-4">
                            <label>Title</label>
                            <input type="text" name="title" class="border rounded w-full"
                                   value="{{ $game->title }}" required>
                        </div>
                        <div class="mt-4">
    <label>Icon URL (optional)</label>
    <input type="url" name="icon_url" class="border rounded w-full"
           value="{{ $game->icon_url }}">
</div>

                        <div class="mt-4">
                            <label>Platform</label>
                            <input type="text" name="platform" class="border rounded w-full"
                                   value="{{ $game->platform }}">
                        </div>

                        <div class="mt-4">
                            <label>Genre</label>
                            <input type="text" name="genre" class="border rounded w-full"
                                   value="{{ $game->genre }}">
                        </div>

                        <div class="mt-4">
                            <label>Rank</label>
                            <input type="text" name="rank" class="border rounded w-full"
                                   value="{{ $game->rank }}">
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
