<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session;

use App\Models\Event;
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
    public function __invoke(Request $request, Event $event, EventStage $eventStage)
    {
        $eventStage->load([
            'event',
            'masterMatchType',
            'masterMatchCategory',
            'eventSessionParticipants' => function ($q) {
                $q->has('masterParticipant');
            },
        ]);
        $eventSessions = $eventStage->eventSessions()
        // ->withCount(['eventSessions'])
            ->orderBy('session', 'asc')
            ->get();
        $eventSessions->load([
            'eventStage',
            'eventSessionParticipants' => function ($q) {
                $q->has('masterParticipant');
            },
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
            'eventSessions' => $eventSessions,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
