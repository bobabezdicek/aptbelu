<?php

namespace App\Livewire\Admin;

use Livewire\Component;

#[Layout('layouts.admin')]
class ReservationDetail extends Component
{
    public function render()
    {
        return view('livewire.admin.reservation-detail');
    }
}
