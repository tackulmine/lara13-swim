<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session\Participant;

use App\Libraries\FormFields;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CreateController extends BaseController
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
        $formFields = new FormFields($eventSessionParticipant);
        $formFields = $formFields->generateForm();

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
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'event' => $event,
            'eventStage' => $eventStage,
            'eventSession' => $eventSession,
            'eventSessionOptions' => $eventSessionOptions,
            'eventSessionParticipantModel' => $eventSessionParticipant,
            'eventSessionParticipant' => $formFields,
            'prestasi' => '',
            'participantOptions' => $this->participantOptions->prepend('-- select --', ''),
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }
}
