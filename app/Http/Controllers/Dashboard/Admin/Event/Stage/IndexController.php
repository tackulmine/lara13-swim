<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IndexController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request, Event $event)
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
        $eventStages = $event->eventStages()
        // ->withCount(['eventSessions'])
            ->orderBy('order_number', 'asc')
            ->orderBy('number', 'asc')
            ->get();
        $eventStages->load([
            'masterMatchType',
            'masterMatchCategory',
            'eventSessions',
            'eventSessionParticipants' => function ($q) {
                $q->has('masterParticipant');
            },
        ]);

        $breadcrumbs = [
            route('dashboard.admin.event.index') => 'Kompetisi',
            '' => $this->moduleName,
        ];

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}
                - ".$event->name,
            'event' => $event,
            'eventStages' => $eventStages,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
