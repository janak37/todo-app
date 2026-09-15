<?php

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

new class extends Component
{
    public string $email = '';

    public function sendResetLink()
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return route('livewire.password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);
        });

        Password::sendResetLink(['email' => $this->email]);

        ResetPassword::createUrlUsing(null);

        session()->flash('success', 'If an account exists for that email, a password reset link has been sent.');
    }
};
?>

<div class="mx-auto max-w-md">
    <div class="mb-8 mt-8"><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-600">Reset access</p><h1 class="mt-2 text-4xl font-black tracking-tight">Forgot password<span class="text-amber-500">.</span></h1><p class="mt-3 text-slate-500">Enter your email and we'll send you a link to reset your password.</p></div>

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="sendResetLink" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div>
            <label for="email" class="mb-2 block text-sm font-bold">Email</label>
            <input id="email" type="email" wire:model="email" class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
            @error('email')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center justify-between gap-4 border-t border-slate-100 pt-6">
            <a href="{{ route('livewire.login') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Back to login</a>
            <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-700">Send reset link</button>
        </div>
    </form>
</div>