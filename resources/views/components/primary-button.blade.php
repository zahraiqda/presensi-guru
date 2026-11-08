<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-biru border border-emas/40 rounded-xl font-semibold text-xs text-emas-terang uppercase tracking-wider hover:bg-biru-tua focus:bg-biru-tua active:bg-biru-tua focus:outline-none focus:ring-2 focus:ring-emas focus:ring-offset-2 shadow-xs transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
