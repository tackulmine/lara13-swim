<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage;

use App\Models\Event;
use App\Models\EventStage;
use App\Models\MasterMatchCategory;
use App\Models\MasterMatchType;
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
    public function __invoke(Request $request, Event $event)
    {
        $rules = [
            'number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique(EventStage::table())->where(function ($query) use ($event) {
                    return $query->where('event_id', $event->id);
                }),
            ],
            'master_match_type_id' => 'required|exists:'.MasterMatchType::table().',id',
            'master_match_category_id' => 'required|exists:'.MasterMatchCategory::table().',id',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $validatedData = $validatedData + $request->all();

        if (! $event->eventStages()->create(array_filter($validatedData, 'trim'))) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$event->name' GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index', $event->id)
            ->withSuccess("{$this->moduleName} '$event->name' telah disimpan!");
    }
}
