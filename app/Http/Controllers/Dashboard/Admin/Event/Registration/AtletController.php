<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Registration;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\MasterParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AtletController extends BaseController
{
    public function __invoke(Request $request, Event $event)
    {
        $participantTable = (new MasterParticipant)->getTable();
        $eventRegistrationTable = (new EventRegistration)->getTable();
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
            // ->orderBy("{$eventRegistrationTable}.coach_name")
            // ->orderBy("{$participantTable}.name")
            ->orderBy("{$eventRegistrationTable}.id")
            ->get();
        $eventRegistrations->load([
            'event',
            'types',
            'masterMatchCategory',
            'masterParticipant.masterSchool',
        ]);

        $breadcrumbs = [
            route($this->parentRouteName.'index') => $this->parentModuleName,
            route($this->baseRouteName.'index', $event) => "{$this->moduleName} {$this->parentModuleName} - {$event->name}",
            '' => 'Daftar Atlet',
        ];

        $this->globalData = [
            'pageTitle' => 'Daftar Peserta '.($request->estafet ? 'Estafet' : ($request->individual ? 'Individu' : '')),
            'event' => $event,
            'eventRegistrations' => $eventRegistrations,
            'breadcrumbs' => $breadcrumbs,
            'estafetCatIds' => $estafetCatIds,
            'individualCatIds' => $individualCatIds,
        ] + $this->globalData;

        return view($this->baseViewPath.'atlet', $this->globalData);
    }
}
