@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-xs text-merah font-medium space-y-1 mt-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1.5">
                <span>⚠️</span>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif
