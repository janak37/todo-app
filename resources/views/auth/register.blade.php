@extends('layouts.app')
@section('title', 'Register · Taskflow')

@section('content')
    <div class="mx-auto max-w-md">
        <div class="mb-8 mt-8"><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-600">Get started</p><h1 class="mt-2 text-4xl font-black tracking-tight">Create account<span class="text-amber-500">.</span></h1></div>
        <form action="{{ route('register') }}" method="POST" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            <x-form-input name="name" label="Name" required />
            <x-form-input name="email" label="Email" type="email" required />
            <x-form-input name="password" label="Password" type="password" required />
            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-bold">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
            </div>
            <div class="flex items-center justify-between gap-4 border-t border-slate-100 pt-6">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Already have an account?</a>
                <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-700">Create account</button>
            </div>
        </form>
    </div>
@endsection