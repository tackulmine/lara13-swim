<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage;

use App\Libraries\FormFields;
use App\Models\Event;
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
    public function __invoke(Request $request, Event $event, EventStage $eventStage)
    {
        if (! auth()->user()->hasRole('coach')
            and $event->created_by != auth()->id()
        ) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        $formFields = new FormFields($eventStage);
        $formFields = $formFields->generateForm();

        $this->generateOptions();

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} ".str_pad($eventStage->number, 3, 0, STR_PAD_LEFT),
            'event' => $event,
            'eventStage' => $formFields,
            'id' => $eventStage->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }
}
