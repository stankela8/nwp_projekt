<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Sessions for {{ $game->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('games.index') }}" class="text-blue-600">&larr; Back to games</a>

            <div class="mt-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($sessions->isEmpty())
                        <p>No sessions for this game yet.</p>
                    @else
                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Played at</th>
                                    <th class="px-4 py-2">Duration (min)</th>
                                    <th class="px-4 py-2">Mode</th>
                                    <th class="px-4 py-2">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions as $session)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $session->played_at }}</td>
                                        <td class="border px-4 py-2">{{ $session->duration_minutes }}</td>
                                        <td class="border px-4 py-2">{{ $session->mode }}</td>
                                        <td class="border px-4 py-2">{{ $session->notes }}</td>
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
