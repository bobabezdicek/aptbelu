<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Validate;

class ReservationForm extends Component
{
    public ?string $from = null;
    public ?string $to = null;
    public int $guests = 2;

    #[Validate('required|string|max:80')]
    public ?string $first_name = null;

    #[Validate('required|string|max:80')]
    public ?string $last_name = null;

    #[Validate('required|email')]
    public ?string $email = null;

    #[Validate('nullable|string|max:30')]
    public ?string $phone = null;

    #[Validate('nullable|string|max:1000')]
    public ?string $message = null;

    public bool $submitted = false;

    #[On('prefill-reservation')]
    public function prefillReservation(string $from, string $to, int $guests) : void
    {
        $this->from = $from;
        $this->to = $to;
        $this->guests = $guests;
    }

    public function submit(): void
    {
        $this->validate();

        if (!$this->from || !$this->to) {
            $this->addError('from', 'Vyber prosím termín v kalendáři.');
            return;
        }

        $start = Carbon::parse($this->from)->startOfDay();
        $end   = Carbon::parse($this->to)->startOfDay();

        $nights = $start->diffInDays($end);
        if ($nights < 2) {
            $this->addError('to', 'Minimální délka pobytu jsou 2 noci.');
            return;
        }

        if (Reservation::overlapping($start, $end)->exists()) {
            $this->addError('from', 'Bohužel, termín je už obsazený. Zkus jiný.');
            return;
        }

        Reservation::create([
            'from_date'   => $start,
            'to_date'     => $end,
            'guests'      => $this->guests,
            'price_total' => 0,
            'status'      => 'pending',
            'paid'        => false,
            'first_name'  => $this->first_name,
            'last_name'   => $this->last_name,
            'email'       => $this->email,
            'phone'       => $this->phone,
            'message'     => $this->message,
        ]);

        dispatch(new \App\Jobs\SendReservationOwnerNotification($reservation->id))
            ->onQueue('mail')
            ->afterCommit();

        dispatch(new \App\Jobs\SendReservationClientConfirmation($reservation->id))
            ->onQueue('mail')
            ->afterCommit();

        $this->first_name = $this->last_name = $this->email = $this->phone = $this->message = null;
        $this->submitted = true;

    }

    public function render()
    {
        return view('livewire.reservation-form');
    }
}
