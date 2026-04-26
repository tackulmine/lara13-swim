<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class ResultCertificatePerStageExport implements FromView
{
    protected $event;

    protected $eventStage;

    protected $participants;

    public function __construct($event, $eventStage, Collection $participants)
    {
        $this->event = $event;
        $this->eventStage = $eventStage;
        $this->participants = $participants;
    }

    public function view(): View
    {
        return view('dashboard.admin.event.stage.exports.result-certificate', [
            'event' => $this->event,
            'eventStage' => $this->eventStage,
            'participants' => $this->participants,
        ]);
    }
}
