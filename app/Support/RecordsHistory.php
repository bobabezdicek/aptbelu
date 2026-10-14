<?php

namespace App\Support;

use App\Models\HistoryEvent;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait RecordsHistory
{
    public function record(string $event, ?Model $subject = null, ?string $message = null, array $meta = [], ?int $reservationId = null) : HistoryEvent
    {
        $subject ??= $this instanceof Model ? $this : null;
        $causer = Auth::user();

        $reservationId ??= $subject instanceof Reservation ? $subject->getKey() : null;

        return HistoryEvent::create([
            'event'          => $event,
            'subject_type'   => $subject?->getMorphClass(),
            'subject_id'     => $subject?->getKey(),
            'reservation_id' => $reservationId,
            'causer_type'    => $causer?->getMorphClass(),
            'causer_id'      => $causer?->getKey(),
            'message'        => $message,
            'meta'           => $meta,
        ]);
    }
}
