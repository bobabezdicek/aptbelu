<section id="calendar" class="section container">
    <div class="text-center mb-8 ">
        <h2 class="text-center text-4xl md:text-5xl font-extrabold tracking-tight mb-3">
            Kalendář <span class="text-[var(--accent)]">dostupnosti</span>
        </h2>
        <p class="text-center text-slate-600 mb-10 font-semibold">
            Zkontrolujte dostupnost apartmánu a naplánujte si svůj pobyt
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- ====== LEVÝ SLUPEC: KALENDÁŘ ====== --}}
        <div class="card p-6 md:p-8 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight capitalize">
                    {{ $first->locale('cs')->translatedFormat('F Y') }}
                </h2>
                <div class="flex gap-2">
                    <button wire:click="prev" class="h-10 w-10 grid place-items-center rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200 cursor-pointer">
                        ←
                    </button>
                    <button wire:click="next" class="h-10 w-10 grid place-items-center rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200 cursor-pointer">
                        →
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-7 gap-3 mb-3 text-xs uppercase tracking-wide text-slate-500">
                @foreach (['Po','Út','St','Čt','Pá','So','Ne'] as $w)
                    <div class="px-2">{{ $w }}</div>
                @endforeach
            </div>

            <div class="grid grid-cols-7 gap-3">
                @foreach ($days as $day)
                    @php
                        $inMonth = $day->month === $first->month;
                        $iso     = $day->format('Y-m-d');
                        $booked  = isset($this->booked[$iso]);

                        $hasFrom = (bool) $this->from;
                        $hasTo   = (bool) $this->to;

                        $isStart = $hasFrom && $iso === $this->from;
                        $isEnd   = $hasTo   && $iso === $this->to;

                        $inRange = false;
                        if ($hasFrom && $hasTo) {
                            $inRange = $iso >= $this->from && $iso <= $this->to;
                        }

                        $disabledCheckout = false;
                        if ($hasFrom && !$hasTo) {
                            $fromPlus1 = \Carbon\CarbonImmutable::parse($this->from)->addDay()->format('Y-m-d');
                            $disabledCheckout = ($iso === $this->from) || ($iso === $fromPlus1);
                        }

                        $baseBtn     = 'h-16 md:h-20 rounded-2xl grid place-items-center border';
                        $greenStrong = 'bg-emerald-100 border-emerald-300 text-emerald-900 font-semibold'; // start
                        $greenLight  = 'bg-emerald-50 border-emerald-200';                                  // uvnitř + end
                        $idle        = 'bg-slate-50 hover:bg-slate-100 border-slate-100 cursor-pointer';
                        $disabled    = 'bg-slate-50 border-slate-100 opacity-50 cursor-not-allowed';
                    @endphp

                    @if (!$inMonth)
                        <div class="h-16 md:h-20 rounded-2xl bg-slate-50 opacity-50"></div>

                    @elseif ($booked)
                        <div class="h-16 md:h-20 rounded-2xl bg-red-100 text-red-600 grid place-items-center select-none">
                            {{ $day->day }}
                        </div>

                    @else
                        @php
                            $classes =
                              $isStart ? "$baseBtn $greenStrong" :
                              (($inRange || $isEnd) ? "$baseBtn $greenLight" :
                              ($disabledCheckout ? "$baseBtn $disabled" : "$baseBtn $idle"));
                        @endphp

                        @if ($disabledCheckout)
                            <div class="{{ $classes }}" aria-disabled="true">
                                <span class="text-slate-700">{{ $day->day }}</span>
                            </div>
                        @else
                            <button
                                wire:click="select('{{ $iso }}')"
                                class="{{ $classes }}"
                            >
                                <span class="text-slate-800">{{ $day->day }}</span>
                            </button>
                        @endif
                    @endif
                @endforeach
            </div>

            <div class="mt-4 flex items-center justify-between">
                <div class="text-sm text-slate-600">
                    @if ($this->from && !$this->to)
                        Vybraný začátek:
                        <span class="font-medium">{{ \Carbon\CarbonImmutable::parse($this->from)->locale('cs')->translatedFormat('j. n. Y') }}</span>
                        <span class="ml-2 text-xs opacity-75">(min. 2 noci)</span>
                    @elseif ($this->from && $this->to)
                        Termín: <span class="font-medium">
              {{ \Carbon\CarbonImmutable::parse($this->from)->locale('cs')->translatedFormat('j. n. Y') }}
              – {{ \Carbon\CarbonImmutable::parse($this->to)->locale('cs')->translatedFormat('j. n. Y') }}
            </span>
                        <span class="ml-2 text-xs opacity-75">({{ $this->nights }} nocí)</span>
                    @else
                        Vyber začátek pobytu (check-in).
                    @endif
                </div>
                <button wire:click="clear" class="text-xs text-slate-500 hover:text-slate-700 underline">Vymazat výběr
                </button>
            </div>
        </div>

        {{-- ====== PRAVÝ SLUPEC: INFORMACE + CENÍK ====== --}}
        <div class="flex flex-col gap-6">
            <div class="card p-6">
                <h4 class="font-semibold mb-4">Informace</h4>
                <div class="flex flex-wrap gap-2 text-sm">
                    <span class="chip chip--green">Vybraný termín</span>
                    <span class="chip chip--red">Obsazeno</span>
                    <span class="chip chip--muted">Dostupné</span>
                </div>

                <div class="mt-4 text-sm text-slate-600 space-y-1">
                    <div>Počet osob:
                        <select wire:model.live="people" class="inline-block ml-2 border rounded-lg px-2 py-1 text-sm">
                            @for ($i=2; $i<=4; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    @if ($this->from && !$this->to)
                        <div>Vybrán check-in:
                            <span class="font-medium">{{ \Carbon\CarbonImmutable::parse($this->from)->locale('cs')->translatedFormat('j. n. Y') }}</span>
                        </div>
                        <div class="text-xs opacity-75">Klikni na další den (min. 2 noci) pro dokončení výběru.</div>
                    @elseif ($this->from && $this->to)
                        <div>Vybraný termín:
                            <span class="font-medium">
                {{ \Carbon\CarbonImmutable::parse($this->from)->locale('cs')->translatedFormat('j. n. Y') }}
                – {{ \Carbon\CarbonImmutable::parse($this->to)->locale('cs')->translatedFormat('j. n. Y') }}
              </span>
                            <span class="ml-1">({{ $this->nights }} nocí)</span>
                        </div>
                    @else
                        <div>Vyber termín kliknutím do kalendáře.</div>
                    @endif
                </div>
            </div>

            <div class="card p-6">
                <h4 class="font-semibold mb-4">Ceník</h4>

                {{-- (volitelně) cena za noc pro současný výběr --}}
                <div class="flex items-center justify-between">
                    <span>Za noc (aktuální výběr)</span>
                    <span class="font-semibold">
      {{ number_format($this->nights > 0 ? ($this->total - $this->cleaningFee) / $this->nights : 0, 0, ',', ' ') }} Kč
    </span>
                </div>

                <div class="flex items-center justify-between pb-3 border-b mt-3">
                    <span>Úklidový poplatek</span>
                    <span class="font-semibold">{{ number_format($this->cleaningFee, 0, ',', ' ') }} Kč</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="font-semibold">Celkem</span>
                    <span class="font-extrabold text-[var(--accent)] text-lg">
      {{ number_format($this->total, 0, ',', ' ') }} Kč
    </span>
                </div>

{{--                <a href="#rezervace" wire:click="reserve" class="btn btn-primary w-full mt-4 disabled:opacity-50 disabled:cursor-not-allowed"--}}
{{--                    @disabled(!($this->from && $this->to))>--}}
{{--                    Rezervovat--}}
{{--                </a>--}}
            </div>
        </div>
    </div>
</section>
