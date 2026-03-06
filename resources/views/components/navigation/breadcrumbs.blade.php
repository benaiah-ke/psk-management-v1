@props(['items' => []])

<nav class="mb-4">
    <ol class="flex items-center gap-1 text-sm text-gray-500">
        @foreach($items as $item)
            @if(!$loop->last)
                <li>
                    @if(isset($item['url']))
                        <a href="{{ $item['url'] }}" class="hover:text-gray-700">{{ $item['label'] }}</a>
                    @else
                        <span>{{ $item['label'] }}</span>
                    @endif
                </li>
                <li><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
            @else
                <li class="font-medium text-gray-900">{{ $item['label'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>
