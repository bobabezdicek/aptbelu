<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class PriceRule extends Model
{
    protected $fillable = [
        'name','date_from','date_to','days_of_week',
        'price_per_night','min_nights','priority','is_active','note',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to'   => 'date',
        'days_of_week' => 'array',
        'is_active' => 'boolean',
    ];

    public function appliesToDate(Carbon $d): bool
    {
        if (!$this->is_active) return false;
        if ($this->date_from && $d->lt($this->date_from)) return false;
        if ($this->date_to   && $d->gte($this->date_to->copy()->addDay())) return false;

        if ($dow = $this->days_of_week) {
            // Carbon: mon,tue,wed,thu,fri,sat,sun
            $map = [
                'mon','tue','wed','thu','fri','sat','sun'
            ];
            $hit = $map[$d->dayOfWeekIso - 1]; // 1=Mon
            return in_array($hit, $dow, true);
        }
        return true;
    }

    /** Scope: aktivní, seřazeno podle priority sestupně */
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true)->orderByDesc('priority');
    }
}
