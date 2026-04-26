<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session\Participant;

use App\Libraries\FormFields;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EditController extends BaseController
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
        EventSession $eventSession,
        EventSessionParticipant $eventSessionParticipant
    ) {
        if (! auth()->user()->hasRole('coach')
            and $event->created_by != auth()->id()
        ) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        if ($eventSessionParticipant->event_session_id != $eventSession->id) {
            return redirect()
                ->route($this->baseRouteName.'edit', array_merge([
                    'event' => $event->id,
                    'eventStage' => $eventStage->id,
                    'eventSession' => $eventSessionParticipant->event_session_id,
                    'eventSessionParticipant' => $eventSessionParticipant->id,
                ], getQueryParams()));
        }

        $formFields = new FormFields($eventSessionParticipant);
        $formFields = $formFields->generateForm();

        $eventSessionParticipant->load(['masterParticipant.styles', 'masterParticipant.masterSchool']);
        $eventSession->load('eventStage');
        $this->generateOptions();

        $eventSessionOptions = $eventStage->eventSessions->pluck('session', 'id')->prepend('-- select --', '');

        // // stage
        // $eventStage = $eventSession->eventStage->load('eventSessionParticipants');
        // // event
        // $event = $eventStage->event->load([
        //     'eventStages',
        //     'eventStages.masterMatchType',
        //     'eventStages.masterMatchCategory',
        //     'eventSessions',
        //     'eventSessions.eventSessionParticipants',
        // ]);

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} ".$eventSessionParticipant->masterParticipant->name.'
                ('.(optional($eventSessionParticipant->masterParticipant->masterSchool)->name ?? '-').')',
            'event' => $event,
            'eventStage' => $eventStage,
            'eventSession' => $eventSession,
            'eventSessionOptions' => $eventSessionOptions,
            'eventSessionParticipant' => $formFields,
            'id' => $eventSessionParticipant->id,
            'prestasi' => optional(optional($eventSessionParticipant->masterParticipant->styles->where('id', $eventStage->masterMatchType->id)->first())->pivot)->point_text ?? '',
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }
}
