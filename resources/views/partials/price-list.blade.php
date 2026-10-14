<section id="price-list" class="section">
    <div class="text-center mb-10">
        <h2 class="text-center text-4xl md:text-5xl font-extrabold tracking-tight mb-3">
            Ceník <span class="text-[var(--accent)]">ubytování</span>
        </h2>
        <p class="text-center text-slate-600 mb-10 font-semibold">
            Přehledné ceny podle počtu hostů. Cena zahrnuje veškeré vybavení, Wi-Fi i parkování.
        </p>
    </div>

    {{-- Tabulka cen podle počtu osob --}}
    <div class="max-w-3xl mx-auto">
        <div class="card p-6 md:p-8">
            <table class="w-full text-sm md:text-base">
                <thead class="text-slate-500 border-b">
                <tr>
                    <th class="py-3 text-left font-semibold">Počet osob</th>
                    <th class="py-3 text-right font-semibold">Cena za noc</th>
                </tr>
                </thead>
                <tbody class="divide-y text-slate-700">
                <tr>
                    <td class="py-3">2 osoby</td>
                    <td class="py-3 text-right font-semibold">2 000 Kč</td>
                </tr>
                <tr>
                    <td class="py-3">3 osoby</td>
                    <td class="py-3 text-right font-semibold">2 500 Kč</td>
                </tr>
                <tr>
                    <td class="py-3">4 osoby</td>
                    <td class="py-3 text-right font-semibold">2 800 Kč</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- doplňkové informace --}}
    <div class="max-w-4xl mx-auto mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card p-6 text-center">
            <div class="text-3xl mb-2">🧹</div>
            <h3 class="font-semibold mb-1">Úklidový poplatek</h3>
            <p class="text-slate-600 text-sm">Fixní poplatek 299 Kč za pobyt (jednorázově).</p>
        </div>

        <div class="card p-6 text-center">
            <div class="text-3xl mb-2">⏱️</div>
            <h3 class="font-semibold mb-1">Minimální délka pobytu</h3>
            <p class="text-slate-600 text-sm">2 noci (krátkodobé pobyty po domluvě).</p>
        </div>

        <div class="card p-6 text-center">
            <div class="text-3xl mb-2">🔑</div>
            <h3 class="font-semibold mb-1">Čas příjezdu a odjezdu</h3>
            <p class="text-slate-600 text-sm">Check-in od 15:00, check-out do 10:00</p>
        </div>
    </div>

    {{-- poznámka --}}
    <p class="text-center text-xs text-slate-500 mt-8 max-w-2xl mx-auto">
        Ceny jsou za celý apartmán na jednu noc podle počtu hostů. Všechny poplatky jsou konečné – neúčtujeme žádné
        skryté příplatky.
    </p>
</section>
