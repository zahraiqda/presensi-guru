@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2 p-3.5 rounded-xl bg-hijau-muda border border-hijau/20 text-hijau text-xs font-semibold shadow-xs']) }}>
        <svg class="w-4 h-4 shrink-0 text-hijau" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ $status }}</span>
    </div>
@endif
