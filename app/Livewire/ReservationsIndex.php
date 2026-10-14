<?php

namespace App\Livewire;

use App\Models\Reservation;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use Livewire\Component;

class ReservationsIndex extends Component
{
    use WithPagination;

    // Filtry
    public string $q = '';
    public string $status = '';         // pending|confirmed|cancelled|''
    public string $paid = '';           // '1'|'0'|''
    public ?string $from = null;
    public ?string $to   = null;
    public string $sort = 'from_date';
    public string $dir  = 'asc';

    // Modal + formulář
    public bool $showForm = false;
    public ?int $editingId = null;

    #[Validate('required|date')]
    public string $f_from = '';

    #[Validate('required|date|after:f_from')]
    public string $f_to = '';

    #[Validate('required|integer|min:1|max:12')]
    public int $f_guests = 1;

    #[Validate('required|integer|min:0')]
    public int $f_price_total = 0;

    #[Validate('required|in:pending,confirmed,cancelled')]
    public string $f_status = 'pending';

    public bool $f_paid = false;

    #[Validate('nullable|string|max:80')]
    public ?string $f_first_name = null;

    #[Validate('nullable|string|max:80')]
    public ?string $f_last_name = null;

    #[Validate('required|email')]
    public string $f_email = '';

    public ?string $f_phone = null;
    public ?string $f_message = null;

    public function render()
    {
        $q = Reservation::query();

        if ($this->q !== '') {
            $search = '%'.trim($this->q).'%';
            $q->where(function ($w) use ($search) {
                $w->where('first_name', 'like', $search)
                    ->orWhere('last_name',  'like', $search)
                    ->orWhere('email',      'like', $search);
            });
        }
        if ($this->status !== '') $q->where('status', $this->status);
        if ($this->paid !== '')   $q->where('paid', (bool)$this->paid);
        if ($this->from)          $q->whereDate('from_date', '>=', $this->from);
        if ($this->to)            $q->whereDate('to_date',   '<=', $this->to);

        $q->orderBy($this->sort, $this->dir);

        return view('livewire.reservations-index', [
            'items' => $q->paginate(10),
        ]);
    }

    public function sortBy(string $col): void
    {
        if ($this->sort === $col) {
            $this->dir = $this->dir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $col; $this->dir = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->q=''; $this->status=''; $this->paid=''; $this->from=null; $this->to=null;
        $this->sort='from_date'; $this->dir='asc';
        $this->resetPage();
    }

    // Quick akce
    public function togglePaid(int $id): void
    {
        $r = Reservation::findOrFail($id);
        $r->paid = !$r->paid;
        $r->save();
    }
    public function confirm(int $id): void
    {
        Reservation::whereKey($id)->update(['status'=>'confirmed']);
    }
    public function cancel(int $id): void
    {
        Reservation::whereKey($id)->update(['status'=>'cancelled']);
    }

    // Formulář
    public function openCreate(): void
    {
        $this->editingId = null;
        $this->fill([
            'f_from' => now()->toDateString(),
            'f_to'   => now()->addDays(2)->toDateString(),
            'f_guests'=> 2, 'f_price_total'=>0, 'f_status'=>'pending', 'f_paid'=>false,
            'f_first_name'=>null, 'f_last_name'=>null, 'f_email'=>'', 'f_phone'=>null, 'f_message'=>null
        ]);
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $r = Reservation::findOrFail($id);
        $this->editingId = $r->id;
        $this->fill([
            'f_from' => $r->from_date->toDateString(),
            'f_to'   => $r->to_date->toDateString(),
            'f_guests' => $r->guests,
            'f_price_total' => $r->price_total,
            'f_status' => $r->status,
            'f_paid'   => (bool)$r->paid,
            'f_first_name' => $r->first_name,
            'f_last_name'  => $r->last_name,
            'f_email'      => $r->email,
            'f_phone'      => $r->phone,
            'f_message'    => $r->message,
        ]);
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'from_date'   => $this->f_from,
            'to_date'     => $this->f_to,
            'guests'      => $this->f_guests,
            'price_total' => $this->f_price_total,
            'status'      => $this->f_status,
            'paid'        => $this->f_paid,
            'first_name'  => $this->f_first_name,
            'last_name'   => $this->f_last_name,
            'email'       => $this->f_email,
            'phone'       => $this->f_phone,
            'message'     => $this->f_message,
        ];

        Reservation::updateOrCreate(['id'=>$this->editingId], $data);

        $this->showForm = false;
        $this->editingId = null;
        session()->flash('ok','Uloženo.');
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Reservation::whereKey($id)->delete();
        $this->resetPage();
    }
}
