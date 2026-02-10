<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gaming Tracker</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950 text-slate-100">
    <div class="min-h-screen">
        <!-- Top nav -->
        <nav class="border-b border-slate-800 bg-slate-900/90 backdrop-blur">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-14">
                <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-lg bg-gradient-to-br from-fuchsia-500 to-cyan-400 flex items-center justify-center">
                        <span class="text-xs font-bold">GT</span>
                    </div>
                    <div>
                        <div class="text-sm uppercase tracking-widest text-slate-400">Gaming Tracker</div>
                        <div class="text-xs text-slate-500">Track your grind across all games</div>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('games.index') }}" class="text-slate-300 hover:text-cyan-300">
                        Games
                    </a>
                    <a href="{{ route('play-sessions.index') }}" class="text-slate-300 hover:text-cyan-300">
                        Sessions
                    </a>

                    <div class="border-l border-slate-700 pl-4 flex items-center gap-2">
                        <span class="text-slate-400 text-xs">Logged in as</span>
                        <span class="font-semibold text-slate-100">{{ Auth::user()->name ?? '' }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                class="ml-2 text-xs px-2 py-1 rounded-full border border-slate-600 hover:border-fuchsia-500 hover:text-fuchsia-300">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page heading -->
        @isset($header)
            <header class="bg-slate-900/60 border-b border-slate-800">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
