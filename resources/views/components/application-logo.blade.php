<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-3']) }}>
    <div class="relative flex items-center justify-center w-11 h-11 rounded-2xl bg-white p-1 shadow-md shadow-blue-950/20 ring-2 ring-amber-400/50">
        <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Islam Al-Amanah Salem" class="w-full h-full object-contain">
        <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500 ring-2 ring-slate-900"></span>
        </span>
    </div>
    <div class="flex flex-col text-left">
        <span class="text-base font-extrabold tracking-tight text-white leading-none">AL-AMANAH</span>
        <span class="text-[10px] font-semibold tracking-wider text-amber-300 uppercase mt-0.5">Presensi Guru &middot; Salem</span>
    </div>
</div>
