<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;

new class extends Component {
    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request('email', '');
    }

    public function resetPassword()
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user
                    ->forceFill([
                        'password' => Hash::make($this->password),
                        'remember_token' => Str::random(60),
                    ])
                    ->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            },
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('success', 'Your password has been reset. You can now log in.');

            return $this->redirectRoute('livewire.login', navigate: true);
        }

        $this->addError('email', __($status));
    }
};
?>

<div class="mx-auto max-w-md">
    <div class="mb-8 mt-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-600">Choose a new password</p>
        <h1 class="mt-2 text-4xl font-black tracking-tight">Reset password<span class="text-amber-500">.</span></h1>
    </div>

    <form wire:submit="resetPassword" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div>
            <label for="email" class="mb-2 block text-sm font-bold">Email</label>
            <input id="email" type="email" wire:model="email"
                class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
            @error('email')
                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password" class="mb-2 block text-sm font-bold">New password</label>
            <input id="password" type="password" wire:model="password"
                class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
            @error('password')
                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-bold">Confirm new password</label>
            <input id="password_confirmation" type="password" wire:model="password_confirmation"
                class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
        </div>
        <div class="flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
            <button type="submit"
                class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-700">Reset
                password</button>
        </div>
    </form>
</div>
