@props(['label' => null, 'name', 'options' => [], 'required' => false, 'placeholder' => 'Select an option...', 'selected' => null])

<div>
    @if($label)
        <label for="{{ $name }}" class="mb-1 block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <select name="{{ $name }}" id="{{ $name }}"
            {{ $attributes->merge(['class' => 'block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500 disabled:bg-gray-50 disabled:text-gray-500' . ($errors->has($name) ? ' border-red-300 focus:border-red-500 focus:ring-red-500' : '')]) }}
            @if($required) required @endif>
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $text)
            <option value="{{ $value }}" @selected(old($name, $selected) == $value)>{{ $text }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
