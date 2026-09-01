<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Taskflow')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-100 text-slate-900 antialiased">
    <header class="border-b border-slate-900/10 bg-white/80 backdrop-blur">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-5 py-5 sm:px-8">
            <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 text-lg font-bold tracking-tight">
                <span class="grid size-9 place-items-center rounded-xl bg-amber-400 text-sm font-black text-slate-950">TF</span>
                <span>Taskflow</span>
            </a>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700">
                        <span class="text-lg leading-none">+</span> New task
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Log in</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700">Sign up</a>
                @endauth
            </div>
        </div>
    </header>
    <main class="mx-auto max-w-5xl px-5 py-10 sm:px-8 sm:py-14">
        @if(session('success'))
            <x-toast type="success" :message="session('success')" />
        @endif
        @if(session('error'))
            <x-toast type="error" :message="session('error')" />
        @endif
        @yield('content')
    </main>
</body>
</html>