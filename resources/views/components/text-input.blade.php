@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-garis focus:border-biru focus:ring-2 focus:ring-biru/20 rounded-xl shadow-xs text-sm bg-white text-teks placeholder-redup/70 outline-none transition-all']) !!}>
