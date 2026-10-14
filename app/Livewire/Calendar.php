<?php

namespace App\Livewire;

use Carbon\CarbonImmutable;
use Livewire\Component;

class Calendar extends Component
{
    public CarbonImmutable $month;

    public ?string $from = null;
    public ?string $to = null;

    public int $people = 2;

    public array $booked = [];

    public int $cleaningFee;

    public function mount(?string $ym = null) : void
    {
        CarbonImmutable::setLocale('cs');

        $this->month = $ym
            ? CarbonImmutable::createFromFormat('Y-m', $ym)->startOfMonth()
            : CarbonImmutable::now()->startOfMonth();

        $this->booked = array_fill_keys(config('calendar.booked', []), true);
        $this->cleaningFee = (int) config('calendar.cleaning_fee', 299);

        // obnova ze session (volitelné)
        $this->from = session('calendar_from');
        $this->to = session('calendar_to');

        $this->emitPrefillIfReady();
    }

    public function prev() : void
    {
        $this->month = $this->month->subMonth()->startOfMonth();
    }

    public function next() : void
    {
        $this->month = $this->month->addMonth()->startOfMonth();
    }

    /** Klik na den v kalendáři */
    public function select(string $isoDate) : void
    {
        // nesmí být obsazený
        if (isset($this->booked[$isoDate])) {
            return;
        }

        // 1) když ještě nemáme from -> nastavíme začátek
        if (!$this->from) {
            $this->from = $isoDate;
            $this->to = null;
            $this->persist();

            return;
        }

        // 2) když máme from, ale nemáme to -> pokus o uzavření intervalu
        if ($this->from && !$this->to) {
            $start = CarbonImmutable::parse($this->from);
            $end = CarbonImmutable::parse($isoDate);

            // nelze vybrat stejný nebo dřívější den; check-out musí být > check-in
            if ($end->lessThanOrEqualTo($start)) {
                // bereme to jako nový začátek
                $this->from = $isoDate;
                $this->to = null;
                $this->persist();

                return;
            }

            $nights = $start->diffInDays($end); // end je check-out (vyloučené)
            // min 2 noci
            if ($nights < 2) {
                // necháme from a čekáme na validní to
                return;
            }

            // kontrola kolize s obsazenými dny v intervalu [from, to)
            foreach ($this->eachDate($start, $end) as $d) {
                $iso = $d->format('Y-m-d');
                if (isset($this->booked[$iso])) {
                    // kolize -> reset a použij isoDate jako nový začátek
                    $this->from = $isoDate;
                    $this->to = null;
                    $this->persist();

                    return;
                }
            }

            // validní interval
            $this->to = $isoDate;
            $this->persist();

            return;
        }

        // 3) když už máme uzavřený interval -> třetí klik začne nový výběr
        $this->from = $isoDate;
        $this->to = null;
        $this->persist();
    }

    public function clear() : void
    {
        $this->from = $this->to = null;
        $this->persist();
    }

    /** Počet nocí (0 pokud není uzavřený interval) */
    public function getNightsProperty() : int
    {
        if (!$this->from || !$this->to) return 0;

        return CarbonImmutable::parse($this->from)->diffInDays(CarbonImmutable::parse($this->to));
    }

    /** Celková cena = suma (cena za noc podle dne + osob) za všechny noci + úklid */
    public function getTotalProperty() : int
    {
        $nights = $this->nights;
        if ($nights === 0) return 0;

        $sum = 0;
        $prices = config('calendar.prices', []);

        $start = CarbonImmutable::parse($this->from);
        $end = CarbonImmutable::parse($this->to);

        foreach ($this->eachDate($start, $end) as $d) {
            // den v týdnu: mon/tue/wed...
            $key = strtolower($d->isoFormat('ddd'));
            $byPeople = $prices[$key] ?? [];
            // cena pro daný počet osob (pokud není, vezmeme nejvyšší definovanou)
            $perNight = $byPeople[$this->people] ?? (count($byPeople) ? end($byPeople) : 0);
            $sum += (int) $perNight;
        }

        return $sum + $this->cleaningFee;
    }

    /** Grid dnů pro aktuální měsíc */
    protected function buildGrid() : array
    {
        $start = $this->month->startOfWeek(CarbonImmutable::MONDAY);
        $end = $this->month->endOfMonth()->endOfWeek(CarbonImmutable::SUNDAY);

        $days = [];
        for ($d = $start; $d <= $end; $d = $d->addDay()) {
            $days[] = $d;
        }

        return compact('days', 'start', 'end');
    }

    /** Iteruje po nocích: [start, end) (end je check-out, vyloučený) */
    protected function eachDate(CarbonImmutable $start, CarbonImmutable $end) : \Generator
    {
        for ($d = $start; $d < $end; $d = $d->addDay()) {
            yield $d;
        }
    }

    private function emitPrefillIfReady(): void
    {
        if ($this->from && $this->to && $this->people > 0) {
            $this->dispatch(
                'prefill-reservation',
                from: $this->from,
                to: $this->to,
                guests: $this->people
            )->to(\App\Livewire\ReservationForm::class);
        }
    }

    protected function persist() : void
    {
        session([
            'calendar_from' => $this->from,
            'calendar_to'   => $this->to,
        ]);

        $this->emitPrefillIfReady();
    }

//    protected function dispatchToForm(): void
//    {
//        $fromHuman = $this->from ? \Carbon\CarbonImmutable::parse($this->from)->translatedFormat('d.m.Y') : null;
//        $toHuman   = $this->to   ? \Carbon\CarbonImmutable::parse($this->to)->translatedFormat('d.m.Y')   : null;
//
//        $this->dispatch('calendar-changed', [
//            'from'       => $this->from,
//            'to'         => $this->to,
//            'from_human' => $fromHuman,
//            'to_human'   => $toHuman,
//            'guests'     => $this->guests,
//        ]);
//
//    }

    // posilani dat do formulare
    public function reserve(): void
    {
        if (!$this->from || !$this->to || $this->people < 1) {
            return;
        }

        $this->dispatch(
            'prefill-reservation',
            from: $this->from,
            to: $this->to,
            guests: $this->people
        )->to(\App\Livewire\ReservationForm::class);
    }

    public function updatedPeople($value) : void
    {
        $this->people = max(1, min((int) $value, 4));
        $this->emitPrefillIfReady();
    }


    public function render()
    {
        $grid = $this->buildGrid();

        return view('livewire.calendar', [
            'first' => $this->month,
            'days'  => $grid['days'],
        ]);
    }
}
