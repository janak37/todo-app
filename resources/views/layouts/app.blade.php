<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Taskflow' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-100 text-slate-900 antialiased">
    <header class="border-b border-slate-900/10 bg-white/80 backdrop-blur">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-5 py-5 sm:px-8">
            <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 text-lg font-bold tracking-tight">
                <span class="grid size-9 place-items-center rounded-xl bg-amber-400 text-sm font-black text-slate-950">TF</span>
                <span>Taskflow</span>
            </a>
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700">
                <span class="text-lg leading-none">+</span> New task
            </a>
        </div>
    </header>
    <main class="mx-auto max-w-5xl px-5 py-10 sm:px-8 sm:py-14">
        @if(session('success'))
            <div class="mb-8 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800" role="status">
                <span class="grid size-6 place-items-center rounded-full bg-emerald-500 text-xs font-bold text-white">✓</span>
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>