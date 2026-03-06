@props(['color' => 'gray', 'size' => 'sm'])

@php
$colors = [
    'gray' => 'bg-gray-100 text-gray-700',
    'red' => 'bg-red-100 text-red-700',
    'green' => 'bg-green-100 text-green-700',
    'blue' => 'bg-blue-100 text-blue-700',
    'yellow' => 'bg-yellow-100 text-yellow-800',
    'purple' => 'bg-purple-100 text-purple-700',
    'orange' => 'bg-orange-100 text-orange-700',
    'indigo' => 'bg-indigo-100 text-indigo-700',
];
$sizes = [
    'xs' => 'px-1.5 py-0.5 text-[10px]',
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-1 text-sm',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full font-medium ' . ($colors[$color] ?? $colors['gray']) . ' ' . ($sizes[$size] ?? $sizes['sm'])]) }}>
    {{ $slot }}
</span>
