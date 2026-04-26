<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Http\Controllers\Dashboard\Admin\BaseController;
use App\Models\Event;
use App\Models\EventSessionParticipant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventSessionParticipantController extends BaseController
{
    public $participantOptions;

    protected $baseParentRouteName;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Peserta';
        $this->baseParentRouteName = 'dashboard.admin.event.';
        $this->baseRouteName = 'dashboard.admin.event.participant.';
        $this->baseViewPath = 'dashboard.admin.event.participant.';

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
    public function index(Request $request, Event $event)
    {
        $event->load([
            'eventStages',
            'eventStages.masterMatchType',
            'eventStages.masterMatchCategory',
            'eventSessions',
            'eventSessions.eventSessionParticipants' => function ($q) {
                $q->has('masterParticipant');
            },
        ]);
        $eventSessionIds = $event->eventSessions->pluck('id');
        // dd($eventSessionIds);
        $eventSessionParticipants = EventSessionParticipant::with([
            'eventSession',
            'eventSession.eventStage',
            'eventSession.eventStage.masterMatchType',
            'eventSession.eventStage.masterMatchCategory',
            'masterParticipant',
            'masterParticipant.masterSchool',
        ])
            ->has('masterParticipant')
            ->whereIn('event_session_id', $eventSessionIds)
            ->orderBy('event_session_id', 'asc')
            ->orderBy('track', 'asc')
            ->get();

        $breadcrumbs = [
            route('dashboard.admin.event.index') => 'Kompetisi',
            '' => $this->moduleName,
        ];

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName} - ".$event->name,
            'event' => $event,
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
