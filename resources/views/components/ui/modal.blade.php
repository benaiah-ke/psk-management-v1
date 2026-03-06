@props(['name', 'maxWidth' => 'lg'])

@php
$widths = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
];
@endphp

<div x-data="{ show: false }"
     x-on:open-modal.window="if ($event.detail === '{{ $name }}') { show = true; document.body.classList.add('overflow-hidden'); }"
     x-on:close-modal.window="if ($event.detail === '{{ $name }}') { show = false; document.body.classList.remove('overflow-hidden'); }"
     x-on:keydown.escape.window="if (show) { show = false; document.body.classList.remove('overflow-hidden'); }"
     x-show="show" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">

    {{-- Backdrop --}}
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500/75" @click="show = false; document.body.classList.remove('overflow-hidden')"></div>

    {{-- Modal --}}
    <div x-show="show" x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="relative w-full {{ $widths[$maxWidth] ?? $widths['lg'] }} rounded-xl bg-white p-6 shadow-xl">
        {{-- Close button --}}
        <button type="button"
                @click="show = false; document.body.classList.remove('overflow-hidden')"
                class="absolute right-4 top-4 rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span class="sr-only">Close</span>
        </button>
        {{ $slot }}
    </div>
</div>
