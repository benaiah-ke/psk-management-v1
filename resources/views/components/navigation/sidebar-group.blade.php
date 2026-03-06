@props(['label'])

<div class="mb-4">
    <p class="mb-1 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">{{ $label }}</p>
    {{ $slot }}
</div>
