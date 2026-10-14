<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryEvent extends Model
{
    protected $fillable = [
        'event', 'subject_type', 'subject_id',
        'reservation_id',
        'causer_type', 'causer_id',
        'message', 'meta',
    ];

    protected $casts = ['meta' => 'array'];

    public function subject()     { return $this->morphTo(); }
    public function causer()      { return $this->morphTo(); }
    public function reservation() { return $this->belongsTo(Reservation::class); }
}
