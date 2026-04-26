<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Registration;

use App\Exports\EventRegistrationSchoolsExport;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SchoolController extends BaseController
{
    public function __invoke(Request $request, Event $event)
    {
        $participantTable = (new MasterParticipant)->getTable();
        $schoolTable = (new MasterSchool)->getTable();
        $eventRegistrationTable = (new EventRegistration)->getTable();

        // CALC participants per schools
        $eventRegistrations = EventRegistration::query()
            ->select("{$eventRegistrationTable}.*", "{$participantTable}.name")
            ->join($participantTable, "{$participantTable}.id", '=', "{$eventRegistrationTable}.master_participant_id")
            ->where("{$eventRegistrationTable}.event_id", $event->id);

        $eventCategories = $event->categories->pluck('name', 'id');
        $individualCatIds = $eventCategories->filter(function ($value, $key) {
            return ! Str::contains($value, 'RELAY');
        })->keys()->all();
        $estafetCatIds = $eventCategories->filter(function ($value, $key) {
            return Str::contains($value, 'RELAY');
        })->keys()->all();
        // dd($eventCategories, $individualCatIds, $estafetCatIds);

        if ($request->individual == 1) {
            $eventRegistrations = $eventRegistrations->whereIn('master_match_category_id', $individualCatIds);
        }
        if ($request->estafet == 1) {
            $eventRegistrations = $eventRegistrations->whereIn('master_match_category_id', $estafetCatIds);
        }

        $eventRegistrations = $eventRegistrations
        // ->orderBy("{$eventRegistrationTable}.id")
            ->get();

        $eventRegistrations->load([
            'event',
            'types',
            // 'masterMatchCategory',
            'masterParticipant.masterSchool',
        ]);

        $totalTagihan = [];
        foreach ($eventRegistrations as $eventRegistration) {
            $masterSchoolId = $eventRegistration->masterParticipant->masterSchool->id;
            if (! isset($totalTagihan[$masterSchoolId])) {
                $totalTagihan[$masterSchoolId] = 0;
            }
            $relay = $eventRegistration->types->filter(function ($type) use ($eventRegistration) {
                return Str::contains($eventRegistration->masterMatchCategory->name, 'RELAY');
            });
            $type = count($relay) ? 'relay' : 'normal';
            $tagihan = $eventRegistration->getTotalTagihan($type);

            $totalTagihan[$masterSchoolId] += $tagihan;
        }
        // dd($totalTagihan);

        $schools = MasterSchool::query()
            ->select("{$schoolTable}.*")
            ->join($participantTable, "{$participantTable}.master_school_id", '=', "{$schoolTable}.id")
            ->join($eventRegistrationTable, "{$eventRegistrationTable}.master_participant_id", '=', "{$participantTable}.id")
            ->join('event_registration_style', 'event_registration_style.event_registration_id', '=', "{$eventRegistrationTable}.id")
            ->withCount(['masterParticipants' => function ($q) use ($eventRegistrationTable, $event) {
                $q->whereHas('eventRegistrations', function ($q) use ($eventRegistrationTable, $event) {
                    $q->where("{$eventRegistrationTable}.event_id", $event->id);
                });
            }])
        // ->has('masterParticipants.eventRegistrations.types')
            ->where("{$eventRegistrationTable}.event_id", $event->id)
            ->whereNull("{$participantTable}.deleted_at")
            ->whereNull("{$eventRegistrationTable}.deleted_at")
            ->groupBy("{$schoolTable}.id")
            ->orderBy("{$schoolTable}.name")
            ->get();

        if ($request->has('download')) {
            $filename = $event->slug.'-registration-schools';

            $export = new EventRegistrationSchoolsExport($event, $schools, $totalTagihan);

            return Excel::download($export, $filename.'.xlsx');
        }

        $breadcrumbs = [
            route($this->parentRouteName.'index') => $this->parentModuleName,
            route($this->baseRouteName.'index', $event) => "{$this->moduleName} {$this->parentModuleName} - {$event->name}",
            '' => 'Daftar '.__('Sekolah'),
        ];

        $this->globalData = [
            'pageTitle' => 'Daftar '.__('Sekolah'),
            'event' => $event,
            'schools' => $schools,
            'breadcrumbs' => $breadcrumbs,
            'totalTagihan' => $totalTagihan,
        ] + $this->globalData;

        return view($this->baseViewPath.'school', $this->globalData);
    }
}
