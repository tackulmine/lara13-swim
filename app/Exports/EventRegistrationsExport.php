<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class EventRegistrationsExport implements FromView
{
    protected $event;

    protected $eventRegistrations;

    public function __construct($event, Collection $eventRegistrations)
    {
        $this->event = $event;
        $this->eventRegistrations = $eventRegistrations;
    }

    public function view(): View
    {
        return view('dashboard.admin.event.registration.exports.registrations', [
            'event' => $this->event,
            'eventRegistrations' => $this->eventRegistrations,
        ]);
    }
}
