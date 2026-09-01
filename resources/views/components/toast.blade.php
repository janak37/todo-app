@props(['type' => 'success', 'message'])

@php
    $colors = $type === 'error'
        ? ['border' => 'border-rose-200', 'bg' => 'bg-rose-50', 'text' => 'text-rose-800', 'icon' => 'bg-rose-500']
        : ['border' => 'border-emerald-200', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-800', 'icon' => 'bg-emerald-500'];
@endphp

<div
    x-cloak
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 4000)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed right-5 top-24 z-50 flex items-center gap-3 rounded-xl border {{ $colors['border'] }} {{ $colors['bg'] }} px-4 py-3 text-sm font-medium {{ $colors['text'] }} shadow-lg"
    role="{{ $type === 'error' ? 'alert' : 'status' }}"
>
    <span class="grid size-6 shrink-0 place-items-center rounded-full {{ $colors['icon'] }} text-xs font-bold text-white">{{ $type === 'error' ? '!' : '✓' }}</span>
    <span>{{ $message }}</span>
    <button type="button" @click="show = false" class="ml-2 text-lg leading-none opacity-50 hover:opacity-100">&times;</button>
</div>