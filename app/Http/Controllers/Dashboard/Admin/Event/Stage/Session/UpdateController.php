<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventStage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UpdateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request, Event $event, EventStage $eventStage, EventSession $eventSession)
    {
        if (! auth()->user()->hasRole('coach')
            and $event->created_by != auth()->id()
        ) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        $rules = [
            'session' => [
                'required',
                'integer',
                'min:1',
                Rule::unique(EventSession::table())->where(function ($query) use ($eventStage, $eventSession) {
                    return $query->where('event_stage_id', $eventStage->id)
                        ->where('id', '<>', $eventSession->id);
                }),
            ],
            // 'event_stage_id' => 'required|exists:' . EventStage::table() . ',id',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $validatedData = $validatedData + $request->all();

        if (! $eventSession->update($validatedData)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$eventSession->session' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '$eventSession->session' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', array_merge([
                'event' => $event->id,
                'eventStage' => $eventStage->id,
            ], getQueryParams()))
            ->withSuccess("{$this->moduleName} '$eventSession->session' telah diupdate.");
    }
}
