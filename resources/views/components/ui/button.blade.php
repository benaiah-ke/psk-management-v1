@props(['variant' => 'primary', 'size' => 'md', 'href' => null])

@php
$base = 'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50';
$variants = [
    'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500',
    'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-primary-500',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    'success' => 'bg-accent-600 text-white hover:bg-accent-700 focus:ring-accent-500',
    'ghost' => 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:ring-primary-500',
];
$sizes = [
    'xs' => 'px-2.5 py-1.5 text-xs',
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2.5 text-sm',
    'lg' => 'px-5 py-3 text-base',
];
$classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }}>{{ $slot }}</button>
@endif
