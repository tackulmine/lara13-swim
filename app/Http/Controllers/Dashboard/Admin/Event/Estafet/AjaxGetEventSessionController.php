<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Estafet;

use App\Models\Event;
use App\Models\EventSession;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AjaxGetEventSessionController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request, Event $event)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $eventSessionTable = (new EventSession)->getTable();

        $eventSessions = $event->eventSessions()
            ->where('event_stage_id', $request->input('event_stage_id'))
            ->orderBy('session', 'asc')
            ->select([$eventSessionTable.'.id', $eventSessionTable.'.session'])
            ->get();

        foreach ($eventSessions as $eventSession) {
            $data[$eventSession->id] = 'Seri '.$eventSession->session;
        }

        return response($data, 200);
    }
}
