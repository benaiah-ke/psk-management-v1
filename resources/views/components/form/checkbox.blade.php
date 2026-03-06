@props(['label', 'name', 'value' => '1', 'checked' => false])

<label class="flex items-center gap-2">
    <input type="checkbox" name="{{ $name }}" value="{{ $value }}"
           {{ $attributes->merge(['class' => 'h-4 w-4 rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500']) }}
           @checked(old($name, $checked))>
    <span class="text-sm text-gray-700">{{ $label }}</span>
</label>
