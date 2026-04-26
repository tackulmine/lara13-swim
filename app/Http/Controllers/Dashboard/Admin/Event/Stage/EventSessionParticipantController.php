<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage;

use App\Http\Controllers\Dashboard\Admin\BaseController;
use App\Models\Event;
use App\Models\EventStage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventSessionParticipantController extends BaseController
{
    protected $baseParentRouteName;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Peserta';
        $this->baseParentRouteName = 'dashboard.admin.event.stage.';
        $this->baseRouteName = 'dashboard.admin.event.stage.participant.';
        $this->baseViewPath = 'dashboard.admin.event.stage.participant.';

        $this->globalData = array_merge($this->globalData, [
            'baseParentRouteName' => $this->baseParentRouteName,
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, Event $event, EventStage $eventStage)
    {
        $eventStage->load([
            'masterMatchType',
            'masterMatchCategory',
        ]);
        $eventSessionParticipants = $eventStage->eventSessionParticipants()
            ->has('masterParticipant')
            ->orderBy('event_session_id', 'asc')
            ->orderBy('track', 'asc')
            ->get();
        $eventSessionParticipants->load([
            // 'eventSession',
            'eventSession.eventStage',
            // 'masterParticipant',
            'masterParticipant.masterSchool',
        ]);

        $breadcrumbs = [
            route('dashboard.admin.event.index') => 'Kompetisi',
            route('dashboard.admin.event.stage.index', $event) => 'Acara',
            '' => $this->moduleName,
        ];

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName} -
                Acara ".$eventStage->numberFormat.'
                '.$eventStage->masterMatchType->name.'
                '.$eventStage->masterMatchCategory->name.'
                - '.$event->name,
            'event' => $event,
            'eventStage' => $eventStage,
            'eventSessionParticipants' => $eventSessionParticipants,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\EventSessionParticipant  $eventSessionParticipant
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(EventSessionParticipant $eventSessionParticipant)
    // {
    //     //
    // }

    // *
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Models\EventSessionParticipant  $eventSessionParticipant
    //  * @return \Illuminate\Http\Response

    // public function edit(EventSessionParticipant $eventSessionParticipant)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\EventSessionParticipant  $eventSessionParticipant
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, EventSessionParticipant $eventSessionParticipant)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\EventSessionParticipant  $eventSessionParticipant
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(EventSessionParticipant $eventSessionParticipant)
    // {
    //     //
    // }
}
