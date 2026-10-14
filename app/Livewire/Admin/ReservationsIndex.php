<?php

namespace App\Livewire\Admin;

use Livewire\Component;

#[Layout('layouts.admin')]
class ReservationsIndex extends Component
{
    public function render()
    {
        return view('livewire.admin.reservations-index');
    }
}
