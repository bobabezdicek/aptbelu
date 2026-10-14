@php
    $addr1 = config('location.address_line1', 'Deštné v Orlických horách 127');
    $addr2 = config('location.address_line2', '517 91 Deštné v Orlických horách, Česko');
    $amen  = config('location.amenities', []);
    $q     = urlencode("{$addr1}, {$addr2}");

    $mapSrc = "https://maps.google.com/maps?hl=cs&q={$q}&z=16&output=embed";
@endphp

<section id="location" class="container mx-auto px-4 py-16">
    <h2 class="text-center text-4xl md:text-5xl font-extrabold tracking-tight mb-3">
        Lokalita <span class="text-[var(--accent)]">&amp; Okolí</span>
    </h2>
    <p class="text-center text-slate-600 mb-10 font-semibold">
        Apartmán leží přímo v centru města, vše máte na dosah.
    </p>

    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="relative rounded-2xl overflow-hidden ring-1 ring-black/5 aspect-[5/3]">
                <iframe
                    class="absolute inset-0 w-full h-full"
                    src="{{ $mapSrc }}"
                    loading="lazy"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Mapa – {{ $addr1 }}"
                ></iframe>
            </div>

            <div class="flex flex-col sm:flex-row items-left sm:items-center gap-3 p-4 bg-white rounded-2xl mt-3 ring-1 ring-black/5">
                <div class="rounded-xl bg-white px:4 sm:px-0 py-2">
                    <div class="font-medium leading-tight">{{ $addr1 }}</div>
                    <div class="text-sm text-slate-600">{{ $addr2 }}</div>
                </div>

                <a href="https://www.google.com/maps/place/{{ $q }}" target="_blank" rel="noopener"
                   class="sm:ml-auto flex self-start items-center gap-2 h-11 px-5 rounded-xl bg-[var(--accent)] text-white hover:opacity-95">
                    Otevřít v Mapách
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M5 10a1 1 0 011-1h6.586L10.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 01.083 1.32l-.083.094-4 4a1 1 0 01-1.497-1.32l.083-.094L12.586 11H6a1 1 0 01-1-1z"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="space-y-4">
            @foreach ($amen as $a)
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-black/5 p-4 flex items-center gap-3">
                    <div class="grid place-items-center h-10 w-10 rounded-lg bg-slate-100 text-lg leading-none">
                        {{ $a['icon'] ?? '📍' }}
                    </div>
                    <div class="flex-1">
                        <div class="font-medium">{{ $a['title'] ?? '' }}</div>
                        <div class="text-sm text-slate-600">{{ $a['distance'] ?? '' }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
