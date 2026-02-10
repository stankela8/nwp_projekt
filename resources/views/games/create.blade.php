<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Game
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('games.store') }}">
                        @csrf

                        <div class="mt-4">
                            <label>Title</label>
                            <input type="text" name="title" class="border rounded w-full" required>
                        </div>

                        <div class="mt-4">
    <label>Icon URL (optional)</label>
    <input type="url" name="icon_url" class="border rounded w-full">
</div>


                        <div class="mt-4">
                            <label>Platform</label>
                            <input type="text" name="platform" class="border rounded w-full">
                        </div>

                        <div class="mt-4">
                            <label>Genre</label>
                            <input type="text" name="genre" class="border rounded w-full">
                        </div>

                        <div class="mt-4">
                            <label>Rank</label>
                            <input type="text" name="rank" class="border rounded w-full">
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
