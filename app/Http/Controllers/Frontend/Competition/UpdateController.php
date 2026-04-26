<?php

namespace App\Http\Controllers\Frontend\Competition;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use Illuminate\Http\Request;

class UpdateController extends BaseController
{
    public function __invoke(Request $request, string $eventSlug)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();

        // dd($request->toArray());
        $rules = [
            'participants.*.point' => 'required|size:9',
        ];
        $customMessages = [];
        $customAttributes = [];

        $participants = $request->get('participants', []);
        $customAttributes = collect($participants)
            ->mapWithKeys(function ($participants, $index) {
                $detail = EventSessionParticipant::with(['masterParticipant', 'masterParticipant.masterSchool'])->find($index);

                return [
                    "participants.{$index}.point" => 'Poin Peserta '.optional($detail->masterParticipant)->name.' ('.optional(optional($detail->masterParticipant)->masterSchool)->name.')',
                ];
            })
            ->merge($customAttributes)
            ->toArray();
        $validatedData = $request->validate($rules, $customMessages, $customAttributes);

        foreach ($request->participants as $id => $value) {
            // dd($id, $value);
            $attributes = [];
            // $attributes['point_text'] = $value['point'];
            // $attributes['point'] = parsePointToInt($value['point']);
            $attributes['point_text'] = normalizePoint($value['point'], 2);
            $attributes['point'] = ! empty($value['point']) ? parsePointToInt($attributes['point_text']) : null;
            $attributes['point_text_decimal'] = ! empty($value['point']) ? normalizePoint($value['point'], 3) : null;
            $attributes['point_decimal'] = ! empty($attributes['point_text_decimal'])
                ? parsePointToDecimal($attributes['point_text_decimal'], 3)
                : null;
            if (intval($request->participants[$id]['dis_level']) > 0) {
                $attributes['disqualification'] = true;
                $attributes['dis_level'] = $request->participants[$id]['dis_level'];
            }
            $attributes['updated_by'] = auth()->id();
            // dd($attributes);
            $eventSessionParticipant[] = EventSessionParticipant::where('id', $id)->update($attributes);
        }

        $eventStage = EventStage::whereId($request->event_stage)->firstOrFail();
        $eventSession = EventSession::whereId($request->event_session)->firstOrFail();

        $eventSession->load([
            'eventSessionParticipants' => function ($q) {
                $q->has('masterParticipant');
            },
        ]);

        if (count($eventSessionParticipant) == $eventSession->eventSessionParticipants->count()) {
            $eventSession->completed = 1;
            $eventSession->updated_by = auth()->id();
            $eventSession->save();

            // if ($eventSession->save()) {
            //     if ($eventStage->eventSessions()->whereCompleted(0)->doesntExist()) {
            //         $eventStage->completed = 1;
            //         $eventStage->updated_by = auth()->id();
            //         $eventStage->save();
            //     }
            // }

            return back()
                ->withSuccess('Data berhasil disimpan!');
        }

        return back()
            ->withInput()
            ->withErrors(['Data GAGAL disimpan!']);
    }
}
