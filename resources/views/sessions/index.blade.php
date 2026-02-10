<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Your Play Sessions
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('play-sessions.create') }}" class="btn btn-primary">Add Session</a>

            @if(session('status'))
                <div class="mt-4 text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mt-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($sessions->isEmpty())
                        <p>No sessions yet.</p>
                    @else
                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Game</th>
                                    <th class="px-4 py-2">Played at</th>
                                    <th class="px-4 py-2">Duration (min)</th>
                                    <th class="px-4 py-2">Mode</th>
                                    <th class="px-4 py-2">Notes</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions as $session)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $session->game->title }}</td>
                                        <td class="border px-4 py-2">{{ $session->played_at }}</td>
                                        <td class="border px-4 py-2">{{ $session->duration_minutes }}</td>
                                        <td class="border px-4 py-2">{{ $session->mode }}</td>
                                        <td class="border px-4 py-2">{{ $session->notes }}</td>
                                        <td class="border px-4 py-2">
                                            <a href="{{ route('play-sessions.edit', $session) }}" class="text-blue-600">Edit</a>
                                            <form action="{{ route('play-sessions.destroy', $session) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 ml-2"
                                                    onclick="return confirm('Delete this session?')">
                                                    Delete
                                                </button>
                                            </form>
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
