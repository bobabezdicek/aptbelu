<div class="space-y-4">
    {{-- Flash --}}
    @if (session('ok'))
        <div class="text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2">
            {{ session('ok') }}
        </div>
    @endif

    {{-- Filtry / akce --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm ring-1 ring-neutral-200/60">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-2">
            <input type="text" placeholder="Hledat jméno nebo email..." wire:model.debounce.400ms="q"
                   class="col-span-2 rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">

            <select wire:model="status" class="rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">
                <option value="">Stav: všechny</option>
                <option value="pending">pending</option>
                <option value="confirmed">confirmed</option>
                <option value="cancelled">cancelled</option>
            </select>

            <select wire:model="paid" class="rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">
                <option value="">Platba: vše</option>
                <option value="1">zaplacené</option>
                <option value="0">nezaplacené</option>
            </select>

            <input type="date" wire:model="from" class="rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">
            <input type="date" wire:model="to" class="rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">
        </div>

        <div class="mt-3 flex items-center gap-2">
            <button wire:click="resetFilters" class="rounded-lg border px-3 py-2 text-sm hover:bg-neutral-50">Vymazat
                filtry
            </button>
        </div>
    </div>


    <div class="text-right">
        <button wire:click="openCreate" class="rounded-lg bg-neutral-900 text-white px-3 py-2 text-sm">Nová
            rezervace
        </button>
    </div>

    {{-- Tabulka --}}
    <div class="bg-white rounded-2xl p-0 shadow-sm ring-1 ring-neutral-200/60 overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-neutral-50/70 text-neutral-600">
            <tr>
                <th class="py-2.5 px-3 text-left">Jméno</th>
                <th class="py-2.5 px-3">Datum</th>
                <th class="py-2.5 px-3">Počet dní</th>
                <th class="py-2.5 px-3">Počet lidí</th>
                <th class="py-2.5 px-3 text-center">Cena</th>
                <th class="py-2.5 px-3">Stav</th>
                <th class="py-2.5 px-3"></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200/70">
            @forelse($items as $r)
                <tr class="">
                    <td class="py-2.5 px-3">
                        <div class="font-medium">{{ trim(($r->first_name ?? '').' '.($r->last_name ?? '')) ?: $r->email }}</div>
                        <div class="text-xs text-neutral-500">{{ $r->email }}</div>
                    </td>
                    <td class="py-2.5 px-3 text-center">{{ $r->from_date->format('j.n.Y') }}
                        – {{ $r->to_date->format('j.n.Y') }}</td>
                    <td class="py-2.5 px-3 text-center">{{ $r->nights }}</td>
                    <td class="py-2.5 px-3 text-center">{{ $r->guests }}</td>
                    <td class="py-2.5 px-3 text-center">{{ number_format($r->price_total,0,',',' ') }} Kč</td>
                    <td class="py-2.5 px-3 text-center">
                        <button wire:click="togglePaid({{ $r->id }})"
                                class="text-xs rounded px-2 py-0.5 border
                                           {{ $r->paid ? 'bg-[#25903c] text-white border-[#25903c]' : 'bg-[#b91c1b] text-white border-[#b91c1b]' }}">
                            {{ $r->paid ? 'Zaplacené' : 'Nezaplacené' }}
                        </button>
                    </td>
                    <td class="py-2.5 px-3 text-right">
                        <div class="inline-flex gap-2">
                            <button wire:click="edit({{ $r->id }})" class="text-neutral-700 hover:text-neutral-900">
                                Upravit
                            </button>
                            <button wire:click="delete({{ $r->id }})" class="text-neutral-500 hover:text-red-600">
                                Smazat
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="py-6 px-3 text-sm text-neutral-500" colspan="8">Zatím žádné rezervace.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="p-3">{{ $items->links() }}</div>
    </div>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white rounded-2xl w-full max-w-3xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold">{{ $editingId ? 'Upravit rezervaci' : 'Nová rezervace' }}</h3>
                    <button class="text-neutral-600 hover:text-neutral-900"
                            wire:click="$set('showForm', false)">Zavřít
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <label class="space-y-1">
                        <span class="text-xs text-neutral-700">Od</span>
                        <input type="date" wire:model="f_from" class="rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">
                        @error('f_from')
                        <div class="text-xs text-red-600">{{ $message }}</div>@enderror
                    </label>
                    <label class="space-y-1">
                        <span class="text-xs text-neutral-700">Do</span>
                        <input type="date" wire:model="f_to" class="rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">
                        @error('f_to')
                        <div class="text-xs text-red-600">{{ $message }}</div>@enderror
                    </label>

                    <label class="space-y-1">
                        <span class="text-xs text-neutral-700">Hostů</span>
                        <input type="number" min="1" wire:model="f_guests" class="rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">
                        @error('f_guests')
                        <div class="text-xs text-red-600">{{ $message }}</div>@enderror
                    </label>
                    <label class="space-y-1">
                        <span class="text-xs text-neutral-700">Cena celkem (Kč)</span>
                        <input type="number" min="0" wire:model="f_price_total" class="rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">
                        @error('f_price_total')
                        <div class="text-xs text-red-600">{{ $message }}</div>@enderror
                    </label>

                    <label class="space-y-1">
                        <span class="text-xs text-neutral-700">Stav</span>
                        <select wire:model="f_status" class="rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">
                            <option value="pending">pending</option>
                            <option value="confirmed">confirmed</option>
                            <option value="cancelled">cancelled</option>
                        </select>
                        @error('f_status')
                        <div class="text-xs text-red-600">{{ $message }}</div>@enderror
                    </label>
                    <label class="flex items-center gap-2 mt-6">
                        <input type="checkbox" wire:model="f_paid" class="h-4 w-4 accent-neutral-900">
                        <span class="text-sm">Zaplaceno</span>
                    </label>

                    <label class="space-y-1">
                        <span class="text-xs text-neutral-700">Jméno</span>
                        <input type="text" wire:model="f_first_name" class="rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">
                    </label>
                    <label class="space-y-1">
                        <span class="text-xs text-neutral-700">Příjmení</span>
                        <input type="text" wire:model="f_last_name" class="rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">
                    </label>
                    <label class="space-y-1">
                        <span class="text-xs text-neutral-700">Email</span>
                        <input type="email" wire:model="f_email" class="rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">
                        @error('f_email')
                        <div class="text-xs text-red-600">{{ $message }}</div>@enderror
                    </label>
                    <label class="space-y-1">
                        <span class="text-xs text-neutral-700">Telefon</span>
                        <input type="text" wire:model="f_phone" class="rounded-lg border border-neutral-300/70 px-3 py-2 text-sm">
                    </label>

                    <div class="md:col-span-2">
                        <span class="text-xs text-neutral-700">Zpráva</span>
                        <textarea rows="3" wire:model="f_message"
                                  class="w-full rounded-lg border border-neutral-300/70 px-3 py-2 text-sm"></textarea>
                    </div>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button class="rounded-lg border px-3 py-2 text-sm hover:bg-neutral-50"
                            wire:click="$set('showForm', false)">Zrušit
                    </button>
                    <button wire:click="save"
                            class="rounded-lg bg-neutral-900 text-white px-3 py-2 text-sm">Uložit
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
