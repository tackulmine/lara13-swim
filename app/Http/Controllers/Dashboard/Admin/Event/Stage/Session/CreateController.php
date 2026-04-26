<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session;

use App\Libraries\FormFields;
use App\Models\Event;
use App\Models\EventSession;
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
    public function __invoke(Request $request, Event $event, EventStage $eventStage, EventSession $eventSession)
    {
        $formFields = new FormFields($eventSession);
        $formFields = $formFields->generateForm();

        $eventStage->load('event');
        $this->generateOptions($eventStage->event->id);

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'event' => $event,
            'eventStage' => $eventStage,
            'eventSession' => $formFields,
            'stageOptions' => $this->stageOptions->prepend('-- select --', ''),
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }
}
