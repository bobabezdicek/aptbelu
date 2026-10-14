<?php

namespace App\Livewire\Admin;

use App\Models\PriceRule;
use App\Models\PriceOverride;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Validate;

class PricingManager extends Component
{
    // Form – rules
    #[Validate('required|string|max:120')] public $name = '';
    #[Validate('nullable|date')] public $date_from;
    #[Validate('nullable|date|after_or_equal:date_from')] public $date_to;
    public array $days_of_week = []; // ['fri','sat']
    #[Validate('required|integer|min:1')] public $price_per_night = 1000;
    #[Validate('required|integer|min:1')] public $min_nights = 1;
    #[Validate('required|integer|min:0|max:1000')] public $priority = 10;
    public bool $is_active = true;
    public $note = '';
    public ?int $editingId = null;

    // Preview
    public $preview_from;
    public $preview_to;
    public ?int $preview_price = null;

    // Override
    public $override_date;
    public $override_price;

    public function mount(): void
    {
        $this->preview_from = now()->toDateString();
        $this->preview_to   = now()->addDays(3)->toDateString();
    }

    public function edit(int $id): void
    {
        $r = PriceRule::findOrFail($id);
        $this->editingId = $r->id;
        $this->name = $r->name;
        $this->date_from = optional($r->date_from)?->toDateString();
        $this->date_to   = optional($r->date_to)?->toDateString();
        $this->days_of_week = $r->days_of_week ?? [];
        $this->price_per_night = $r->price_per_night;
        $this->min_nights = $r->min_nights;
        $this->priority   = $r->priority;
        $this->is_active  = $r->is_active;
        $this->note       = $r->note;
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->date_from = $this->date_to = null;
        $this->days_of_week = [];
        $this->price_per_night = 1000;
        $this->min_nights = 1;
        $this->priority = 10;
        $this->is_active = true;
        $this->note = '';
    }

    public function save(): void
    {
        $this->validate();
        $data = [
            'name' => $this->name,
            'date_from' => $this->date_from ?: null,
            'date_to'   => $this->date_to ?: null,
            'days_of_week' => $this->days_of_week ?: null,
            'price_per_night' => (int)$this->price_per_night,
            'min_nights' => (int)$this->min_nights,
            'priority'   => (int)$this->priority,
            'is_active'  => (bool)$this->is_active,
            'note'       => $this->note ?: null,
        ];
        PriceRule::updateOrCreate(['id'=>$this->editingId], $data);
        $this->resetForm();
        session()->flash('ok','Uloženo.');
    }

    public function delete(int $id): void
    {
        PriceRule::whereKey($id)->delete();
        if ($this->editingId === $id) $this->resetForm();
    }

    public function addOverride(): void
    {
        $this->validate([
            'override_date'  => 'required|date',
            'override_price' => 'required|integer|min:1',
        ]);
        PriceOverride::updateOrCreate(
            ['date' => $this->override_date],
            ['price_per_night' => (int)$this->override_price, 'is_active' => true]
        );
        $this->override_date = $this->override_price = null;
        session()->flash('ok','Override uložen.');
    }

    public function preview(): void
    {
        $from = Carbon::parse($this->preview_from);
        $to   = Carbon::parse($this->preview_to);
        if ($to->lte($from)) { $this->preview_price = null; return; }

        $sum = 0; $nights = 0;
        for ($d = $from->copy(); $d->lt($to); $d->addDay()) {
            $sum += $this->priceForDate($d);
            $nights++;
        }
        $this->preview_price = $nights ? $sum : null;
    }

    private function priceForDate(Carbon $d): int
    {
        // 1) override má přednost
        if ($ov = \App\Models\PriceOverride::where('is_active', true)->whereDate('date', $d)->first()) {
            return (int)$ov->price_per_night;
        }
        // 2) nejvyšší priorita pravidla, které platí pro ten den
        $rule = PriceRule::active()->get()->first(fn($r) => $r->appliesToDate($d));
        return $rule ? (int)$rule->price_per_night : 0;
    }

    public function render()
    {
        return view('livewire.pricing-manager', [
            'rules' => PriceRule::orderByDesc('priority')->orderBy('date_from')->get(),
            'overrides' => PriceOverride::orderBy('date')->limit(30)->get(),
        ]);
    }
}

