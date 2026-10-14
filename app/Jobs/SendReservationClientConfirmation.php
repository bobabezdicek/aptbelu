<?php

namespace App\Jobs;


use App\Models\Reservation;
use App\Mail\ReservationClientConfirmation;
//use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;

class SendReservationClientConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    public int $tries = 3;
    public int $backoff = 30;

    public function handle(): void
    {
        $reservation = Reservation::find($this->reservationId);
        if (!$reservation || !$reservation->email) return;

        Mail::to($reservation->email)->send(new ReservationClientConfirmation($reservation));
    }
}
