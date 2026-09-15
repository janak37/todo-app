<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function login()
    {
        $validated = $this->validate();

        if (! Auth::attempt($validated, $this->remember)) {
            $this->addError('email', 'Those credentials do not match our records.');

            return;
        }

        session()->regenerate();

        session()->flash('success', 'Welcome back, '.Auth::user()->name.'!');

        return redirect()->route('livewire.tasks.index');
    }
};
?>

<div class="mx-auto max-w-md">
    <div class="mb-8 mt-8"><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-600">Welcome back</p><h1 class="mt-2 text-4xl font-black tracking-tight">Log in<span class="text-amber-500">.</span></h1></div>
    <form wire:submit="login" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div>
            <label for="email" class="mb-2 block text-sm font-bold">Email</label>
            <input id="email" type="email" wire:model="email" class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
            @error('email')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password" class="mb-2 block text-sm font-bold">Password</label>
            <input id="password" type="password" wire:model="password" class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
        </div>
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" wire:model="remember" class="rounded border-slate-300">
                Remember me
            </label>
            <a href="{{ route('livewire.password.request') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Forgot password?</a>
        </div>
        <div class="flex items-center justify-between gap-4 border-t border-slate-100 pt-6">
            <a href="{{ route('livewire.register') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Need an account?</a>
            <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-700">Log in</button>
        </div>
    </form>
</div>