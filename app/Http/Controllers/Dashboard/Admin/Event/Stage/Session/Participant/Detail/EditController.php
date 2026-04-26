<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session\Participant\Detail;

use App\Libraries\FormFields;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use App\Models\MasterMatchCategory;
use App\Models\MasterMatchType;
use App\Models\MasterParticipant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class EditController extends BaseController
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
        if (
            ! auth()->user()->hasRole('coach')
            and $event->created_by != auth()->id()
        ) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        if ($eventSessionParticipant->event_session_id != $eventSession->id) {
            return redirect()
                ->route($this->baseRouteName.'edit', array_merge([
                    'event' => $event->id,
                    'eventStage' => $eventStage->id,
                    'eventSession' => $eventSessionParticipant->event_session_id,
                    'eventSessionParticipant' => $eventSessionParticipant->id,
                ], getQueryParams()));
        }

        // $formFields = new FormFields($eventSessionParticipant);
        // $formFields = $formFields->generateForm();

        $eventSessionParticipant->load([
            'masterParticipant.styles',
            'masterParticipant.masterSchool',
            'participantDetails',
        ]);
        $eventSession->load('eventStage');

        $birthYears = extractNumbers($eventStage->masterMatchCategory->name);

        $masterGroupParticipantIds = MasterParticipant::has('masterSchool')
            ->with('eventSessionParticipants.eventSession.eventStage')
            ->whereHas('eventStages', function ($q) {
                $q->whereIn('master_match_category_id', MasterMatchCategory::where('name', 'like', '%RELAY%')->pluck('id'))
                    ->orWhereIn('master_match_type_id', MasterMatchType::where('name', 'like', '%ESTAFET%')->pluck('id'));
            })
            ->where('master_school_id', $eventSessionParticipant->masterParticipant->masterSchool->id)
            ->when(! Str::contains($eventStage->masterMatchType->name, 'MIX'), function ($q) use ($eventSessionParticipant) {
                $q->where('gender', $eventSessionParticipant->masterParticipant->gender);
            })
            ->when(is_array($birthYears) && count($birthYears), function ($q) use ($birthYears, $eventStage) {
                if (count($birthYears) > 1) {
                    $q->whereBetween('birth_year', [$birthYears[0], $birthYears[1]]);
                } elseif (Str::contains(optional($eventStage->masterMatchCategory)->name, '+')) {
                    $q->where('birth_year', '>=', $birthYears[0]);
                }
            })
            ->pluck('id');
        // ->pluck('name', 'id'); // for debug only
        // dd($masterGroupParticipantIds);

        $masterIndividualParticipants = MasterParticipant::has('masterSchool')
            ->with('masterSchool')
            ->where('master_school_id', $eventSessionParticipant->masterParticipant->masterSchool->id)
            // ->where('gender', $eventSessionParticipant->masterParticipant->gender)
            ->when(! Str::contains($eventStage->masterMatchType->name, 'MIX'), function ($q) use ($eventSessionParticipant) {
                $q->where('gender', $eventSessionParticipant->masterParticipant->gender);
            })
            ->when(is_array($birthYears) && count($birthYears), function ($q) use ($birthYears, $eventStage) {
                if (count($birthYears) > 1) {
                    $q->whereBetween('birth_year', [$birthYears[0], $birthYears[1]]);
                } elseif (Str::contains(optional($eventStage->masterMatchCategory)->name, '+')) {
                    $q->where('birth_year', '>=', $birthYears[0]);
                }
            })
            ->whereNotIn('id', $masterGroupParticipantIds)
            ->orderBy('name')
            ->get();

        $masterParticipantOptions = $masterIndividualParticipants
            ->pluck('name_detail_with_school', 'id')
            ->prepend('-- pilih --', '');

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} ".$eventSessionParticipant->masterParticipant->name.'
                ('.(optional($eventSessionParticipant->masterParticipant->masterSchool)->name ?? '-').')',
            'event' => $event,
            'eventStage' => $eventStage,
            'eventSession' => $eventSession,
            'masterParticipantOptions' => $masterParticipantOptions,
            'eventSessionParticipant' => $eventSessionParticipant,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }
}
