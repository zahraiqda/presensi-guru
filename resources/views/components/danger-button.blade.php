<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-merah border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-wider hover:bg-merah/90 active:bg-merah/95 focus:outline-none focus:ring-2 focus:ring-merah focus:ring-offset-2 transition ease-in-out duration-150 shadow-xs']) }}>
    {{ $slot }}
</button>
