<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Estafet;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EditController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request, Event $event)
    {
        if (
            ! auth()->user()->hasRole('coach')
            and $event->created_by != auth()->id()
        ) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        $eventStages = $event->eventStages()->whereHas('masterMatchType', function ($q) {
            $q->where('name', 'like', '%estafet%');
        })->get();

        $eventStages->load(['masterMatchType', 'masterMatchCategory']);

        $stageOptions = [];
        foreach ($eventStages as $eventStage) {
            $stageOptions[$eventStage->id] = $eventStage->number.'. '.$eventStage->masterMatchType->name.', '.$eventStage->masterMatchCategory->name;
        }

        // dd($stageOptions);

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$event->name}",
            'stageOptions' => collect($stageOptions)->prepend('-- Pilih --', ''),
            'sessionOptions' => [],
            'id' => $event->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        // dd($this->globalData);
        return view($this->baseViewPath.'edit', $this->globalData);
    }
}
