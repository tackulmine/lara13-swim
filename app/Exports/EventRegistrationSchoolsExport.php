<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class EventRegistrationSchoolsExport implements FromView
{
    protected $event;

    protected $schools;

    protected $totalTagihan;

    public function __construct($event, Collection $schools, $totalTagihan)
    {
        $this->event = $event;
        $this->schools = $schools;
        $this->totalTagihan = $totalTagihan;
    }

    public function view(): View
    {
        return view('dashboard.admin.event.registration.exports.registration-schools', [
            'event' => $this->event,
            'schools' => $this->schools,
            'totalTagihan' => $this->totalTagihan,
        ]);
    }
}
