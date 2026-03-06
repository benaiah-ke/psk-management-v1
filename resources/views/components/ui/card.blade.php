@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200 bg-white shadow-sm' . ($padding ? ' p-5' : '')]) }}>
    {{ $slot }}
</div>
