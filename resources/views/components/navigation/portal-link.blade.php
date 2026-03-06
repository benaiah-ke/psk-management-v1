@props(['href', 'active' => false])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'rounded-lg px-3 py-2 text-sm font-medium transition-colors ' . ($active ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900')]) }}>
    {{ $slot }}
</a>
