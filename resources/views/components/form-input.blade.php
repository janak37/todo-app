@props(['label', 'name', 'type' => 'text', 'required' => false])

<div x-data="{
        value: '{{ old($name) }}',
        get missing() { return {{ $required ? 'true' : 'false' }} && this.value.trim().length === 0; }
        @if($type === 'email')
        , get invalidEmail() { return this.value.length > 0 && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value); }
        @endif
    }"
>
    <label for="{{ $name }}" class="mb-2 block text-sm font-bold">{{ $label }}</label>
    <input
        id="{{ $name }}"
        type="{{ $type }}"
        name="{{ $name }}"
        x-model="value"
        @if($required) required @endif
        :class="(submitted && missing) @if($type === 'email') || invalidEmail @endif ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/10' : 'border-slate-300 focus:border-amber-500 focus:ring-amber-500/10'"
        class="w-full rounded-lg border px-4 py-3 outline-none transition focus:ring-4"
    >
    <p x-show="submitted && missing" x-cloak class="mt-2 text-sm text-rose-600">This field is required.</p>
    @if($type === 'email')
        <p x-show="!missing && invalidEmail" x-cloak class="mt-2 text-sm text-rose-600">Please enter a valid email address.</p>
    @endif
    @error($name)<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
</div>