@props(['label', 'value', 'change' => null, 'icon' => null, 'color' => 'primary'])

@php
$iconBg = [
    'primary' => 'bg-primary-100 text-primary-600',
    'green' => 'bg-green-100 text-green-600',
    'blue' => 'bg-blue-100 text-blue-600',
    'red' => 'bg-red-100 text-red-600',
    'yellow' => 'bg-yellow-100 text-yellow-600',
    'purple' => 'bg-purple-100 text-purple-600',
    'orange' => 'bg-orange-100 text-orange-600',
];
@endphp

<x-ui.card>
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $value }}</p>
            @if($change)
                <p class="mt-1 text-sm {{ str_starts_with($change, '+') ? 'text-green-600' : 'text-red-600' }}">{{ $change }}</p>
            @endif
        </div>
        @if($icon)
            <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $iconBg[$color] ?? $iconBg['primary'] }}">
                {{ $icon }}
            </div>
        @endif
    </div>
</x-ui.card>
