<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session\Participant;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use App\Models\MasterParticipant;
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
    public function __invoke(
        Request $request,
        Event $event,
        EventStage $eventStage,
        EventSession $eventSession
    ) {
        $rules = [
            'track' => [
                'required',
                'integer',
                'min:0',
                Rule::unique(EventSessionParticipant::table())->where(function ($query) use ($request, $eventSession) {
                    return $query->where('event_session_id', ($request->event_session_id ?? $eventSession->id));
                }),
            ],
            'disqualification' => 'filled|boolean',
            'master_participant_id' => 'required|exists:'.MasterParticipant::table().',id',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $validatedData['point_text'] = normalizePoint($request->point_text, 2);
        $validatedData['point'] = ! empty($request->point_text) ? parsePointToInt($validatedData['point_text']) : null;
        $validatedData['point_text_decimal'] = ! empty($request->point_text) ? normalizePoint($request->point_text, 3) : null;
        $validatedData['point_decimal'] = ! empty($validatedData['point_text_decimal'])
        ? parsePointToDecimal($validatedData['point_text_decimal'], 3)
        : null;
        if (intval($request->dis_level) > 0) {
            $validatedData['disqualification'] = true;
            $validatedData['dis_level'] = $request->dis_level;
        }
        $validatedData = $validatedData + $request->all();

        // dd($request->toArray(), $validatedData, array_filter($validatedData, 'trim'));
        $eventSessionParticipant = $eventSession->eventSessionParticipants()->create(array_filter($validatedData, 'trim'));
        if (! $eventSessionParticipant) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} Baru GAGAL disimpan!"]);
        }

        // sync prestasi
        $prestasiText = $request->input('prestasi');
        $prestasi = ! empty($prestasiText) ? parsePointToInt($prestasiText) : null;
        $prestasiSet = intval($prestasi) > 0 && ($prestasi != '99.99.99' || $prestasi != '99:99.99' || $prestasi != '09:09.99');

        $masterParticipant = $eventSessionParticipant->masterParticipant;
        if (! empty($masterParticipant)) {
            $masterParticipant->styles()->syncWithoutDetaching([
                $eventStage->master_match_type_id => [
                    'is_no_point' => ($prestasiSet ? 0 : 1),
                    'point' => ($prestasiSet ? $prestasi : null),
                    'point_text' => ($prestasiSet ? $prestasiText : null),
                ],
            ]);
        }

        return redirect()
            ->route($this->baseRouteName.'index', [
                'event' => $event->id,
                'eventStage' => $eventStage->id,
                'eventSession' => $eventSession->id,
            ])
            ->withSuccess("{$this->moduleName} '$masterParticipant->name' telah disimpan!");
    }
}
