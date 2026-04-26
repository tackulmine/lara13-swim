<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Registration;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndexController extends BaseController
{
    public function __invoke(Request $request, Event $event)
    {
        $schoolTable = (new MasterSchool)->getTable();
        $participantTable = (new MasterParticipant)->getTable();
        $eventRegistrationTable = (new EventRegistration)->getTable();
        $eventRegistrations = EventRegistration::query()
            ->select("{$eventRegistrationTable}.*", "{$participantTable}.name")
            ->join($participantTable, "{$participantTable}.id", '=', "{$eventRegistrationTable}.master_participant_id")
            ->join($schoolTable, "{$schoolTable}.id", '=', "{$participantTable}.master_school_id")
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
            // ->orderBy("{$eventRegistrationTable}.coach_name")
            ->orderBy("{$participantTable}.name")
            ->orderBy("{$eventRegistrationTable}.id")
            ->get();

        $eventRegistrations->load([
            'types' => function ($q) use ($event) {
                $masterTypeIds = $event->types()->pluck('id');

                $q->whereIn('master_match_type_id', $masterTypeIds);
            },
            'masterMatchCategory' => function ($q) use ($event) {
                $masterCategoryIds = $event->categories()->pluck('id');

                $q->whereIn('id', $masterCategoryIds);
            },
            'masterParticipant.masterSchool',
        ]);

        // dd($eventRegistrations->first()->types()->first()->pivot);

        $breadcrumbs = [
            route($this->parentRouteName.'index') => $this->parentModuleName,
            '' => "{$this->moduleName} {$this->parentModuleName} - {$event->name}",
        ];

        $this->globalData = [
            'pageTitle' => 'Daftar Nomor Gaya Peserta '.($request->estafet ? 'Estafet' : ($request->individual ? 'Individu' : '')),
            'event' => $event,
            'eventRegistrations' => $eventRegistrations,
            'breadcrumbs' => $breadcrumbs,
            'estafetCatIds' => $estafetCatIds,
            'individualCatIds' => $individualCatIds,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
