<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage;

use App\Libraries\FormFields;
use App\Models\Event;
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
    public function __invoke(Request $request, Event $event, EventStage $eventStage)
    {
        $formFields = new FormFields($eventStage);
        $formFields = $formFields->generateForm();

        $this->generateOptions();

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'event' => $event,
            'eventStage' => $formFields,
            'typeOptions' => $this->typeOptions->prepend('-- select --', ''),
            'categoryOptions' => $this->categoryOptions->prepend('-- select --', ''),
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }
}
