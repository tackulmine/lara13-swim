<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Registration;

use App\Exports\EventRegistrationsExport;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DownloadController extends BaseController
{
    public function __invoke(Request $request, Event $event)
    {
        $participantTable = (new MasterParticipant)->getTable();
        $schoolTable = (new MasterSchool)->getTable();
        $eventRegistrationTable = (new EventRegistration)->getTable();
        $eventRegistrations = EventRegistration::query()
            ->select("{$eventRegistrationTable}.*", "{$participantTable}.name")
            ->join($participantTable, "{$participantTable}.id", '=', "{$eventRegistrationTable}.master_participant_id")
            ->join($schoolTable, "{$schoolTable}.id", '=', "{$participantTable}.master_school_id")
            ->where("{$eventRegistrationTable}.event_id", $event->id)
            ->orderBy("{$schoolTable}.name")
            // ->orderBy("{$eventRegistrationTable}.coach_name")
            ->orderBy("{$participantTable}.name")
            ->orderBy("{$eventRegistrationTable}.id")
            ->get();
        $eventRegistrations->load([
            'types',
            'masterMatchCategory',
            'masterParticipant.masterSchool',
        ]);

        $filename = $event->slug.'-registrations';

        $export = new EventRegistrationsExport($event, $eventRegistrations);

        return Excel::download($export, $filename.'.xlsx');
    }
}
