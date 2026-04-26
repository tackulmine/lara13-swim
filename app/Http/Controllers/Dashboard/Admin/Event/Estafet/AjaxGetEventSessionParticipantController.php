<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Estafet;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\MasterMatchCategory;
use App\Models\MasterMatchType;
use App\Models\MasterParticipant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class AjaxGetEventSessionParticipantController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request, Event $event)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $eventSessionParticipants = $event->eventParticipants()
            ->has('masterParticipant')
            ->where('event_session_id', $request->input('event_session_id'))
            ->orderBy('track', 'asc')
            ->get();

        $eventSessionParticipants->load([
            'masterParticipant.styles',
            'masterParticipant.masterSchool',
            'participantDetails',
        ]);

        $eventSession = EventSession::find($request->input('event_session_id'));

        $eventStage = $eventSession->eventStage;

        $birthYears = extractNumbers(optional($eventStage->masterMatchCategory)->name);

        $masterGroupParticipants = MasterParticipant::has('masterSchool')
            ->with('eventSessionParticipants.eventSession.eventStage')
            ->whereHas('eventStages', function ($q) {
                $q->whereIn('master_match_category_id', MasterMatchCategory::where('name', 'like', '%RELAY%')->pluck('id'))
                    ->orWhereIn('master_match_type_id', MasterMatchType::where('name', 'like', '%ESTAFET%')->pluck('id'));
            })
            ->when(is_array($birthYears) && count($birthYears), function ($q) use ($birthYears, $eventStage) {
                if (count($birthYears) > 1) {
                    $q->whereBetween('birth_year', [$birthYears[0], $birthYears[1]]);
                } elseif (Str::contains(optional($eventStage->masterMatchCategory)->name, '+')) {
                    $q->where('birth_year', '>=', $birthYears[0]);
                }
            })
            ->get();

        $masterIndividualParticipants = MasterParticipant::has('masterSchool')
            ->with('masterSchool')
            ->when(is_array($birthYears) && count($birthYears), function ($q) use ($birthYears, $eventStage) {
                if (count($birthYears) > 1) {
                    $q->whereBetween('birth_year', [$birthYears[0], $birthYears[1]]);
                } elseif (Str::contains(optional($eventStage->masterMatchCategory)->name, '+')) {
                    $q->where('birth_year', '>=', $birthYears[0]);
                }
            })
            ->orderBy('name')
            ->get();

        $this->globalData['eventStage'] = $eventStage;
        $this->globalData['eventSessionParticipants'] = $eventSessionParticipants;
        $this->globalData['masterGroupParticipants'] = $masterGroupParticipants;
        $this->globalData['masterIndividualParticipants'] = $masterIndividualParticipants;

        $data = view($this->baseViewPath.'_estafet-table', $this->globalData)->render();

        return response($data, 200);
    }
}
