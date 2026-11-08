@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-xs text-teks uppercase tracking-wider']) }}>
    {{ $value ?? $slot }}
</label>
