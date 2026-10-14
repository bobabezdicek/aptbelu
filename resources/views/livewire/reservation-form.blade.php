<div id="rezervace" class="card p-6 md:p-8">
    <div class="mb-6">
        <h3 class="text-xl md:text-2xl font-semibold tracking-tight">Rezervační formulář</h3>
        <p class="text-sm text-slate-500 mt-1">Vyplň prosím kontaktní údaje. Údaje o termínu a počtu hostů se vyplní automaticky podle <a class="font-semibold" href="#calendar">zvoleného termínu</a>.</p>
    </div>

    @if($submitted)
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
            Díky! Poptávku jsme přijali. Ozveme se co nejdřív s potvrzením termínu.
        </div>
    @endif

    <form class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5"
          wire:submit.prevent="submit" novalidate>

        <div class="flex flex-col gap-1.5">
            <label for="first_name" class="text-sm font-medium text-slate-700">Jméno <span class="text-red-500">*</span></label>
            <input id="first_name" type="text" autocomplete="given-name"
                   class="h-11 rounded-xl border border-slate-200 px-3 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400"
                   placeholder="Jméno" wire:model.defer="first_name" required>
            @error('first_name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="last_name" class="text-sm font-medium text-slate-700">Příjmení <span class="text-red-500">*</span></label>
            <input id="last_name" type="text" autocomplete="family-name"
                   class="h-11 rounded-xl border border-slate-200 px-3 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400"
                   placeholder="Příjmení" wire:model.defer="last_name" required>
            @error('last_name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="email" class="text-sm font-medium text-slate-700">Email <span class="text-red-500">*</span></label>
            <input id="email" type="email" autocomplete="email"
                   class="h-11 rounded-xl border border-slate-200 px-3 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400"
                   placeholder="např. jana@domena.cz" wire:model.defer="email" required>
            @error('email') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="phone" class="text-sm font-medium text-slate-700">Telefon <span class="text-red-500">*</span></label>
            <input id="phone" type="tel" inputmode="tel" autocomplete="tel"
                   class="h-11 rounded-xl border border-slate-200 px-3 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400"
                   placeholder="+420 123 456 789" wire:model.defer="phone" required>
            @error('phone') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label for="from" class="text-sm font-medium text-slate-700">Od</label>
            <input id="from" type="text" readonly aria-readonly="true"
                   class="h-11 rounded-xl border border-slate-200 px-3 bg-slate-50 text-slate-700 placeholder-slate-400 focus:outline-none"
                   wire:model.live="from">
        </div>
        <div class="flex flex-col gap-1.5">
            <label for="to" class="text-sm font-medium text-slate-700">Do</label>
            <input id="to" type="text" readonly aria-readonly="true"
                   class="h-11 rounded-xl border border-slate-200 px-3 bg-slate-50 text-slate-700 placeholder-slate-400 focus:outline-none"
                   wire:model="to">
        </div>
        <div class="flex flex-col gap-1.5">
            <label for="to" class="text-sm font-medium text-slate-700">Počet hostů</label>
            <input id="to" type="text" readonly aria-readonly="true"
                   class="h-11 rounded-xl border border-slate-200 px-3 bg-slate-50 text-slate-700 placeholder-slate-400 focus:outline-none"
                   wire:model="guests">
        </div>
        <div class="flex flex-col gap-1.5">
            <label for="to" class="text-sm font-medium text-slate-700">Cena</label>
            <input id="to" type="text" readonly aria-readonly="true"
                   class="h-11 rounded-xl border border-slate-200 px-3 bg-slate-50 text-slate-700 placeholder-slate-400 focus:outline-none"
                   wire:model="price">
        </div>
        <div class="md:col-span-2 flex flex-col gap-1.5">
            <label for="message" class="text-sm font-medium text-slate-700">Zpráva (volitelné)</label>
            <textarea id="message" rows="4"
                      class="rounded-xl border border-slate-200 px-3 py-2 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400"
                      placeholder="Např. přání k dětské postýlce, pozdní check-in apod."
                      wire:model.defer="message"></textarea>
            @error('message') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2 mt-1">
            <button type="submit"
                    class="btn btn-primary w-full h-12 text-base font-semibold">
                Odeslat rezervaci
            </button>
            <p class="text-xs text-slate-500 mt-2">Kliknutím na „Odeslat“ souhlasíš se zpracováním údajů pro vyřízení poptávky.</p>
        </div>
    </form>
</div>
