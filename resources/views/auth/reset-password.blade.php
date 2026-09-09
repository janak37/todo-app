@extends('layouts.app')
@section('title', 'Reset password · Taskflow')

@section('content')
    <div class="mx-auto max-w-md">
        <div class="mb-8 mt-8"><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-600">Choose a new password</p><h1 class="mt-2 text-4xl font-black tracking-tight">Reset password<span class="text-amber-500">.</span></h1></div>

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            action="{{ route('password.update') }}"
            method="POST"
            novalidate
            @submit="if (!$el.checkValidity()) { $event.preventDefault(); $store.form.submitted = true; }"
            class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
        >
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <x-form-input name="email" label="Email" type="email" value="{{ $email }}" required />
            <x-form-input name="password" label="New password" type="password" required />
            <x-form-input name="password_confirmation" label="Confirm new password" type="password" required />
            <div class="flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-700">Reset password</button>
            </div>
        </form>
    </div>
@endsection