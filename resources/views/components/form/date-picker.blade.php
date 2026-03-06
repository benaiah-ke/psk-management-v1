@props(['label' => null, 'name', 'required' => false, 'enableTime' => false])

<div>
    @if($label)
        <label for="{{ $name }}" class="mb-1 block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <input type="text" name="{{ $name }}" id="{{ $name }}"
           value="{{ old($name, $attributes->get('value', '')) }}"
           {{ $attributes->merge(['class' => 'block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500' . ($errors->has($name) ? ' border-red-300 focus:border-red-500 focus:ring-red-500' : '')]) }}
           @if($required) required @endif
           x-init="flatpickr($el, { dateFormat: '{{ $enableTime ? 'Y-m-d H:i' : 'Y-m-d' }}', enableTime: {{ $enableTime ? 'true' : 'false' }} })">
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
