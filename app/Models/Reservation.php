<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Carbon\Carbon;

class Reservation extends Model
{
    protected $fillable = [
        'from_date','to_date','guests',
        'price_total','status','paid',
        'first_name','last_name','email','phone','message',
    ];

    protected $casts = [
        'from_date'   => 'date',
        'to_date'     => 'date',
        'guests'      => 'integer',
        'price_total' => 'integer',
        'paid'        => 'boolean',
    ];

    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    public function getNightsAttribute(): int
    {
        return max(0, $this->from_date->diffInDays($this->to_date));
    }

    /** Scope na překryv s intervalem [start, end] */
    public function scopeOverlapping(EloquentBuilder $q, Carbon $start, Carbon $end): EloquentBuilder
    {
        return $q->whereDate('from_date', '<=', $end)
            ->whereDate('to_date', '>=', $start);
    }
}
