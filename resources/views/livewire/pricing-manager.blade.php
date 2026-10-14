<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    {{-- KARTA: Formulář pravidla --}}
    <section class="lg:col-span-1 bg-white rounded-2xl p-5 shadow-sm ring-1 ring-neutral-200/60">
        <h3 class="font-semibold text-neutral-900">Nové pravidlo</h3>

        @if (session('ok'))
            <div class="mt-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2">
                {{ session('ok') }}
            </div>
        @endif

        <div class="mt-4 grid grid-cols-1 gap-3">
            <x-input label="Název" wire:model.defer="name" placeholder="Léto / Víkendy / atd." />

            <div class="grid grid-cols-2 gap-3">
                <x-input type="date" label="Od" wire:model.defer="date_from" />
                <x-input type="date" label="Do" wire:model.defer="date_to" />
            </div>

            {{-- Dny v týdnu --}}
            <div class="space-y-1">
                <div class="text-xs font-medium text-neutral-700">Dny v týdnu <span class="text-neutral-400">(prázdné = všechny)</span></div>
                @php $opts = ['mon'=>'Po','tue'=>'Út','wed'=>'St','thu'=>'Čt','fri'=>'Pá','sat'=>'So','sun'=>'Ne']; @endphp
                <div class="grid grid-cols-7 gap-1.5">
                    @foreach($opts as $k=>$label)
                        <label class="inline-flex items-center gap-2 rounded-md px-2 py-1 text-xs border border-neutral-300/60 bg-white
                                      has-[:checked]:bg-neutral-900 has-[:checked]:text-white has-[:checked]:border-neutral-900 cursor-pointer">
                            <input type="checkbox" class="accent-neutral-900" wire:model.defer="days_of_week" value="{{ $k }}">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <x-input type="number" min="1" step="1" label="Cena / noc (Kč)" wire:model.defer="price_per_night" />
                <x-input type="number" min="1" step="1" label="Min. nocí" wire:model.defer="min_nights" />
            </div>

            <div class="grid grid-cols-2 gap-3 items-end">
                <x-input type="number" min="0" label="Priorita" wire:model.defer="priority" />
                <label class="flex items-center gap-2 text-sm text-neutral-800">
                    <input type="checkbox" class="h-4 w-4 accent-neutral-900" wire:model.defer="is_active">
                    <span>Aktivní</span>
                </label>
            </div>

            <textarea rows="2" wire:model.defer="note"
                      placeholder="Poznámka"
                      class="w-full rounded-lg border border-neutral-300/70 bg-white px-3 py-2 text-sm
                             focus:outline-none focus:ring-2 focus:ring-neutral-900/5 focus:border-neutral-400"></textarea>

            <div class="flex gap-2 pt-1">
                <button wire:click="save"
                        class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 text-white px-3.5 py-2 text-sm hover:bg-neutral-800">
                    Uložit
                </button>
                @if($editingId)
                    <button wire:click="resetForm"
                            class="rounded-lg border px-3.5 py-2 text-sm hover:bg-neutral-50">
                        Zrušit
                    </button>
                @endif
            </div>
        </div>

        <div class="my-5 h-px bg-neutral-200/70"></div>

        {{-- Override konkrétní den --}}
        <h4 class="font-semibold">Override (konkrétní den)</h4>
        <div class="mt-3 grid grid-cols-2 gap-3">
            <x-input type="date" label="Datum" wire:model.defer="override_date" />
            <x-input type="number" min="1" label="Cena (Kč)" wire:model.defer="override_price" />
        </div>
        <div class="mt-3">
            <button wire:click="addOverride"
                    class="rounded-lg border px-3.5 py-2 text-sm hover:bg-neutral-50">
                Přidat / upravit
            </button>
        </div>
    </section>

    {{-- KARTA: Seznam + náhled --}}
    <section class="lg:col-span-2 bg-white rounded-2xl p-5 shadow-sm ring-1 ring-neutral-200/60">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-neutral-900">Pravidla</h3>
        </div>

        @if($rules->isEmpty())
            <p class="mt-3 text-sm text-neutral-500">Zatím žádná pravidla.</p>
        @else
            <div class="mt-3 overflow-x-auto rounded-lg border border-neutral-200/70">
                <table class="min-w-full text-sm">
                    <thead class="bg-neutral-50/60 text-neutral-600">
                    <tr>
                        <th class="py-2.5 px-3 text-left">Název</th>
                        <th class="py-2.5 px-3 text-left">Od–Do</th>
                        <th class="py-2.5 px-3 text-left">Dny</th>
                        <th class="py-2.5 px-3 text-left">Cena</th>
                        <th class="py-2.5 px-3">Min</th>
                        <th class="py-2.5 px-3">Priorita</th>
                        <th class="py-2.5 px-3">Stav</th>
                        <th class="py-2.5 px-3"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200/70">
                    @foreach($rules as $r)
                        <tr class="hover:bg-neutral-50/50">
                            <td class="py-2.5 px-3 font-medium">{{ $r->name }}</td>
                            <td class="py-2.5 px-3">{{ $r->date_from?->format('j.n.Y') ?? '—' }} – {{ $r->date_to?->format('j.n.Y') ?? '—' }}</td>
                            <td class="py-2.5 px-3 text-xs">{{ $r->days_of_week ? implode(',', $r->days_of_week) : 'vše' }}</td>
                            <td class="py-2.5 px-3">{{ number_format($r->price_per_night,0,',',' ') }} Kč</td>
                            <td class="py-2.5 px-3 text-center">{{ $r->min_nights }}</td>
                            <td class="py-2.5 px-3 text-center">{{ $r->priority }}</td>
                            <td class="py-2.5 px-3">
                                    <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs
                                        {{ $r->is_active
                                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                            : 'bg-neutral-100 text-neutral-700 border-neutral-200' }}">
                                        {{ $r->is_active ? 'aktivní' : 'vypnuté' }}
                                    </span>
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <button wire:click="edit({{ $r->id }})" class="text-neutral-700 hover:text-neutral-900 mr-3">Upravit</button>
                                <button wire:click="delete({{ $r->id }})" class="text-red-600 hover:text-red-700">Smazat</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="my-5 h-px bg-neutral-200/70"></div>

        {{-- Náhled výpočtu --}}
        <h3 class="font-semibold">Náhled výpočtu</h3>
        <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3 items-end">
            <x-input type="date" label="Od" wire:model.defer="preview_from" />
            <x-input type="date" label="Do" wire:model.defer="preview_to" />
            <div>
                <button wire:click="preview" class="mt-6 rounded-lg border px-3.5 py-2 text-sm hover:bg-neutral-50">
                    Spočítat
                </button>
            </div>
            <div class="text-right">
                @if(!is_null($preview_price))
                    <div class="text-xs text-neutral-500">Cena za pobyt</div>
                    <div class="text-xl font-semibold">{{ number_format($preview_price,0,',',' ') }} Kč</div>
                @endif
            </div>
        </div>
    </section>
</div>
