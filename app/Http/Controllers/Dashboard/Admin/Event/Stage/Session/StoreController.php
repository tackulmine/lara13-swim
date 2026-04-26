<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventStage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class StoreController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request, Event $event, EventStage $eventStage)
    {
        $eventStageNumber = str_pad($eventStage->number, 3, 0, STR_PAD_LEFT);

        $rules = [
            'session' => [
                'required',
                'integer',
                'min:1',
                Rule::unique(EventSession::table())->where(function ($query) use ($eventStage) {
                    return $query->where('event_stage_id', $eventStage->id);
                }),
            ],
            // 'event_stage_id' => 'required|exists:' . EventStage::table() . ',id',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $validatedData = $validatedData + $request->all();

        if (! $eventStage->eventSessions()->create(array_filter($validatedData, 'trim'))) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$eventStageNumber' GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index', [
                'event' => $event->id,
                'eventStage' => $eventStage->id,
            ])
            ->withSuccess("{$this->moduleName} '$eventStageNumber' telah disimpan!");
    }
}
