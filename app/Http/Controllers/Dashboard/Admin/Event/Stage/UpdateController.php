<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage;

use App\Models\Event;
use App\Models\EventStage;
use App\Models\MasterMatchCategory;
use App\Models\MasterMatchType;
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

        $rules = [
            'number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique(EventStage::table())->where(function ($query) use ($event, $eventStage) {
                    return $query->where('event_id', $event->id)
                        ->where('id', '<>', $eventStage->id);
                }),
            ],
            'master_match_type_id' => 'required|exists:'.MasterMatchType::table().',id',
            'master_match_category_id' => 'required|exists:'.MasterMatchCategory::table().',id',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $validatedData = $validatedData + $request->all();

        if (! $eventStage->update($validatedData)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$eventStage->number' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '$eventStage->number' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', array_merge(['event' => $event->id], getQueryParams()))
            ->withSuccess("{$this->moduleName} '$eventStage->number' telah diupdate.");
    }
}
