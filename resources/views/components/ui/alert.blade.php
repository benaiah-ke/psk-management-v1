@props(['type' => 'info', 'dismissible' => true])

@php
$styles = [
    'success' => 'border-green-200 bg-green-50 text-green-800',
    'error' => 'border-red-200 bg-red-50 text-red-800',
    'warning' => 'border-yellow-200 bg-yellow-50 text-yellow-800',
    'info' => 'border-blue-200 bg-blue-50 text-blue-800',
];
@endphp

<div x-data="{ show: true }" x-show="show" x-transition
     {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-lg border p-4 text-sm ' . ($styles[$type] ?? $styles['info'])]) }}>
    <div class="flex-1">{{ $slot }}</div>
    @if($dismissible)
        <button @click="show = false" class="shrink-0 opacity-60 hover:opacity-100">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    @endif
</div>
