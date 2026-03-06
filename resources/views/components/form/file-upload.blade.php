@props(['label' => null, 'name', 'required' => false, 'accept' => null, 'hint' => null])

<div>
    @if($label)
        <label for="{{ $name }}" class="mb-1 block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <input type="file" name="{{ $name }}" id="{{ $name }}"
           {{ $attributes->merge(['class' => 'block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-primary-700 hover:file:bg-primary-100']) }}
           @if($accept) accept="{{ $accept }}" @endif
           @if($required) required @endif>
    @if($hint)
        <p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
    @endif
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
