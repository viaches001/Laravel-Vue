@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold ml-6']) }}>
    {{ $value ?? $slot }}
</label>
