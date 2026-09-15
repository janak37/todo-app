<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

new class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    public function register()
    {
        $validated = $this->validate();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        session()->flash('success', 'Welcome, '.$user->name.'!');

        return redirect()->route('livewire.tasks.index');
    }
};
?>

<div class="mx-auto max-w-md">
    <div class="mb-8 mt-8"><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-600">Get started</p><h1 class="mt-2 text-4xl font-black tracking-tight">Create account<span class="text-amber-500">.</span></h1></div>
    <form wire:submit="register" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div>
            <label for="name" class="mb-2 block text-sm font-bold">Name</label>
            <input id="name" type="text" wire:model="name" class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
            @error('name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="email" class="mb-2 block text-sm font-bold">Email</label>
            <input id="email" type="email" wire:model="email" class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
            @error('email')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password" class="mb-2 block text-sm font-bold">Password</label>
            <input id="password" type="password" wire:model="password" class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
            @error('password')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-bold">Confirm password</label>
            <input id="password_confirmation" type="password" wire:model="password_confirmation" class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
        </div>
        <div class="flex items-center justify-between gap-4 border-t border-slate-100 pt-6">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Already have an account?</a>
            <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-700">Create account</button>
        </div>
    </form>
</div>