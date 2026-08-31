@extends('layouts.app')
@section('title', 'Log in · Taskflow')

@section('content')
    <div class="mx-auto max-w-md">
        <div class="mb-8 mt-8"><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-600">Welcome back</p><h1 class="mt-2 text-4xl font-black tracking-tight">Log in<span class="text-amber-500">.</span></h1></div>
        <form action="{{ route('login') }}" method="POST" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            <x-form-input name="email" label="Email" type="email" required />
            <x-form-input name="password" label="Password" type="password" required />
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300">
                Remember me
            </label>
            <div class="flex items-center justify-between gap-4 border-t border-slate-100 pt-6">
                <a href="{{ route('register') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Need an account?</a>
                <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-700">Log in</button>
            </div>
        </form>
    </div>
@endsection