@props(['label', 'name', 'type' => 'text', 'required' => false])

<div>
    <label for="{{ $name }}" class="mb-2 block text-sm font-bold">{{ $label }}</label>
    <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ old($name) }}" @if($required) required @endif class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
    @error($name)<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
</div>