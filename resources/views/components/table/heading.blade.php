@props(['sortable' => false, 'column' => null, 'direction' => null])

<th {{ $attributes->merge(['class' => 'px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500']) }}>
    @if($sortable && $column)
        <a href="{{ request()->fullUrlWithQuery(['sort' => $column, 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
           class="group inline-flex items-center gap-1 hover:text-gray-700">
            {{ $slot }}
            <span class="text-gray-400 group-hover:text-gray-600">
                @if(request('sort') === $column)
                    @if(request('direction') === 'asc')
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 9.707l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 7.414l-3.293 3.293a1 1 0 01-1.414-1.414z"/></svg>
                    @else
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M14.707 10.293l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 12.586l3.293-3.293a1 1 0 111.414 1.414z"/></svg>
                    @endif
                @else
                    <svg class="h-3 w-3 opacity-0 group-hover:opacity-100" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                @endif
            </span>
        </a>
    @else
        {{ $slot }}
    @endif
</th>
