<?php

namespace App\Http\Controllers\Frontend\Competition\Ajax;

use App\Http\Controllers\Frontend\Competition\BaseController;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use App\Models\MasterMatchCategory;
use App\Models\MasterSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GetTypesController extends BaseController
{
    public function __invoke(Request $request, string $eventSlug)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();
        $category = MasterMatchCategory::findOrFail($request->input('category'));
        // logger()->info('category: '.$category->name);
        // logger()->info('gender: '.$request->input('gender'));
        // logger()->info(intval(Str::contains(strtolower($category->name), 'relay')));

        $data = [];
        $categoryTypeIds = $event->categoryTypes()
            ->where('master_match_category_id', $request->input('category'))
            ->pluck('master_match_type_id')
            ->toArray();
        // var_dump($categoryTypeIds, $request->input('category')); die;
        $checkboxes = $event->types()
            ->whereIn('id', $categoryTypeIds)
            ->when($request->input('gender') == 'male', function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'LIKE', '%PA')
                        ->orWhere('name', 'LIKE', '%PUTRA');
                });
            })
            ->when($request->input('gender') == 'female', function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'LIKE', '%PI')
                        ->orWhere('name', 'LIKE', '%PUTRI');
                });
            })
            ->when($request->input('gender') == 'mix', function ($q) use ($category) {
                if (Str::contains(strtolower($category->name), 'relay')) {
                    $q->where(function ($q) {
                        // $q->where('name', 'LIKE', '%PA')
                        //     ->orWhere('name', 'LIKE', '%PUTRA')
                        //     ->orWhere('name', 'LIKE', '%PI')
                        //     ->orWhere('name', 'LIKE', '%PUTRI')
                        //     ->orWhere('name', 'LIKE', '%MIX');
                        $q->where('name', 'LIKE', '%MIX');
                    });
                } else {
                    $q->where(function ($q) {
                        $q->where('name', 'NOT LIKE', '%PA')
                            ->where('name', 'NOT LIKE', '%PUTRA')
                            ->where('name', 'NOT LIKE', '%PI')
                            ->where('name', 'NOT LIKE', '%PUTRI')
                            ->where('name', 'NOT LIKE', '%MIX');
                        // $q->where('name', 'NOT LIKE', '%MIX');
                    });
                }
            })
            ->orderBy('ordering', 'asc')
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');

        if (! $checkboxes->count()) {
            return 'Tidak ditemukan gaya.';
        }

        $currentStyles = null;
        $additionalValues = null;
        // get participant event type values
        $masterSchool = MasterSchool::where('name', $request->school)->first();
        // logger()->info('$masterSchool->name');
        if (! empty($masterSchool)) {
            // logger()->debug($masterSchool->name);

            $masterParticipant = $this->getMasterParticipant($event, $masterSchool, $request);

            // logger()->info('$masterParticipant->name');
            if (! empty($masterParticipant)) {
                // logger()->debug($masterParticipant->name);

                // logger()->info('$checkboxes->keys()->all()');
                // logger()->debug($checkboxes->keys()->all());

                $currentStyles = $masterParticipant->styles()->whereIn('master_match_type_id', $checkboxes->keys()->all())->get();

                // logger()->info('$additionalValues');
                if ($currentStyles->isNotEmpty()) {
                    foreach ($currentStyles as $currentStyle) {
                        $additionalValues[$currentStyle->id] = $currentStyle->pivot->point_text;
                    }
                    // logger()->debug($additionalValues);
                }

                // get another style point
                $eventStageTable = (new EventStage)->getTable();
                $eventSessionTable = (new EventSession)->getTable();
                $eventSessionParticipantTable = (new EventSessionParticipant)->getTable();
                $eventSessionParticipants = EventSessionParticipant::select($eventSessionParticipantTable.'.*', $eventStageTable.'.master_match_type_id')
                    ->join($eventSessionTable, $eventSessionTable.'.id', '=', $eventSessionParticipantTable.'.event_session_id')
                    ->join($eventStageTable, $eventStageTable.'.id', '=', $eventSessionTable.'.event_stage_id')
                    ->whereIn($eventStageTable.'.master_match_type_id', $checkboxes->keys()->all())
                    ->where($eventSessionParticipantTable.'.master_participant_id', $masterParticipant->id)
                    ->where($eventSessionParticipantTable.'.disqualification', false)
                    ->get();

                // logger()->info('additional $additionalValues');
                if ($eventSessionParticipants->isNotEmpty()) {
                    foreach ($eventSessionParticipants as $eventSessionParticipant) {
                        if (empty($additionalValues[$eventSessionParticipant->master_match_type_id])) {
                            $additionalValues[$eventSessionParticipant->master_match_type_id] = $eventSessionParticipant->point_text;
                        }

                        if (! empty($additionalValues[$eventSessionParticipant->master_match_type_id])
                            && parsePointToInt($eventSessionParticipant->point_text) < parsePointToInt($additionalValues[$eventSessionParticipant->master_match_type_id])
                        ) {
                            $additionalValues[$eventSessionParticipant->master_match_type_id] = $eventSessionParticipant->point_text;
                        }
                    }
                    // logger()->debug($additionalValues);
                }
            }
        }

        $data['checkboxes'] = $checkboxes;
        $data['values'] = [];
        $data['name'] = 'style[]';
        $data['additionalName'] = 'style_value[]';
        // $data['additionalValues'] = $currentStyles;
        $data['additionalValues'] = $additionalValues;
        $data['separator'] = 'block';

        return view('front.competition._checkboxes-form', $data)->render();
    }
}
