<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        session()->flash('success', 'You have been logged out.');

        return $this->redirectRoute('livewire.login', navigate: true);
    }
};
?>

<button type="button" wire:click="logout" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Log out</button>
