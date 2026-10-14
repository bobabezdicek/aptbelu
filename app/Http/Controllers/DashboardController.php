<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today  = Carbon::today();
        $next30 = Carbon::today()->addDays(30)->endOfDay();

        $upcoming = Reservation::query()
            ->whereIn('status', ['pending','confirmed'])
            ->whereDate('from_date', '>=', $today)
            ->orderBy('from_date')
            ->limit(5)
            ->get();

        [$bookedNightsNext30, $nightsCapacity] = $this->bookedNightsInRange($today, $next30);
        $occupancyNext30 = $nightsCapacity > 0 ? round(($bookedNightsNext30 / $nightsCapacity) * 100) : 0;

        $startOfMonth = now()->startOfMonth();
        $endOfMonth   = now()->endOfMonth();

        $monthRevenue = Reservation::query()
            ->where('status', 'confirmed')
            // ->where('paid', true)
            ->whereBetween('from_date', [$startOfMonth, $endOfMonth])
            ->sum('price_total');

        $guestsNext30 = Reservation::overlapping($today, $next30)
            ->whereIn('status', ['pending','confirmed'])
            ->sum('guests');

        return view('pages.admin.dashboard', compact(
            'upcoming','bookedNightsNext30','nightsCapacity','occupancyNext30',
            'monthRevenue','guestsNext30','today','next30'
        ));
    }

    /** Vrací [zabookované_noci, kapacita_nocí] v okně; počítáme jen confirmed */
    private function bookedNightsInRange(Carbon $start, Carbon $end): array
    {
        $capacityNights = $start->diffInDays($end);
        if ($capacityNights <= 0) return [0, 0];

        $reservations = \App\Models\Reservation::overlapping($start, $end)
            ->where('status', 'confirmed')
            ->get(['from_date','to_date']);

        $booked = 0;
        foreach ($reservations as $r) {
            $from = $r->from_date->copy()->startOfDay();
            $to   = $r->to_date->copy()->startOfDay();
            $clampedFrom = $from->max($start);
            $clampedTo   = $to->min($end);
            $booked += max($clampedFrom->diffInDays($clampedTo), 0);
        }
        return [$booked, $capacityNights];
    }
}
