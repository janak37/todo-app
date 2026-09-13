<?php

use Livewire\Component;

new class extends Component
{
    public $count = 0;

    public function increment()
    {
        $this->count++;
    }
};
?>

<div class="p-8">
    <h1 class="text-2xl font-bold">Livewire is working! Count: {{ $count }}</h1>
    <button wire:click="increment" class="mt-4 rounded-lg bg-slate-900 px-5 py-3 text-white">Click me</button>
</div>