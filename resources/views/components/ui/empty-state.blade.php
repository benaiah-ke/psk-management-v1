@props(['title', 'description' => null, 'icon' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 px-6 py-12 text-center']) }}>
    @if($icon)
        <div class="mb-4 text-gray-400">{{ $icon }}</div>
    @endif
    <h3 class="text-sm font-medium text-gray-900">{{ $title }}</h3>
    @if($description)
        <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
    @endif
    @if($slot->isNotEmpty())
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
