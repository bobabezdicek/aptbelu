<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Reservation;
use App\Mail\ReservationOwnerNotification;
//use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReservationOwnerNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $reservationId)
    {
    }

    public int $tries = 3;
    public int $backoff = 30;

    public function handle(): void
    {
        $reservation = Reservation::find($this->reservationId);
        if (!$reservation) return;

        $ownerEmail = config('mail.owner_address') ?? config('mail.from.address');
        if (!$ownerEmail) return;

        Mail::to($ownerEmail)->send(new ReservationOwnerNotification($reservation));
    }
}
