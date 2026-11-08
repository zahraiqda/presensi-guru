<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-white border border-garis rounded-xl font-semibold text-xs text-teks uppercase tracking-wider shadow-xs hover:bg-krem focus:outline-none focus:ring-2 focus:ring-biru focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
