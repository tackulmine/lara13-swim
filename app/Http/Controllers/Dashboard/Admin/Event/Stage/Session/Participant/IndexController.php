<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session\Participant;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventStage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IndexController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(
        Request $request,
        Event $event,
        EventStage $eventStage,
        EventSession $eventSession
    ) {
        $eventSession->load([
            'eventStage',
            'eventSessionParticipants' => function ($q) {
                $q->has('masterParticipant');
            },
        ]);
        // participants
        $eventSessionParticipants = $eventSession->eventSessionParticipants()
            // ->withCount(['eventSessionParticipants'])
            ->has('masterParticipant')
            ->orderBy('track', 'asc')
            ->get();
        $eventSessionParticipants->load([
            'masterParticipant.styles',
            'masterParticipant.masterSchool',
            'participantDetails',
        ]);

        // // stage
        // $eventStage->load([
        //     'masterMatchType',
        //     'masterMatchCategory',
        //     // 'eventSessionParticipants',
        // ]);
        // // event
        // $event = $eventStage->event->load([
        //     'eventStages',
        //     'eventStages.masterMatchType',
        //     'eventStages.masterMatchCategory',
        //     'eventSessions',
        //     'eventSessions.eventSessionParticipants',
        // ]);

        $breadcrumbs = [
            route('dashboard.admin.event.index') => 'Kompetisi',
            route('dashboard.admin.event.stage.index', $event) => 'Acara',
            route('dashboard.admin.event.stage.session.index', [$event, $eventStage]) => 'Seri',
            '' => $this->moduleName,
        ];

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName} Seri ".$eventSession->session.' -
                Acara '.$eventStage->numberFormat.'
                '.$eventStage->masterMatchType->name.'
                '.$eventStage->masterMatchCategory->name.'
                - '.$event->name,
            'event' => $event,
            'eventStage' => $eventStage,
            'eventSession' => $eventSession,
            'eventSessionParticipants' => $eventSessionParticipants,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
