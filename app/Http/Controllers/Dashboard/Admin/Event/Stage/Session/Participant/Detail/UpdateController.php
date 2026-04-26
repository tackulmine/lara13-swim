<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session\Participant\Detail;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use App\Models\MasterParticipant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpdateController extends BaseController
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

        $eventSessionParticipant->load(['masterParticipant', 'masterParticipant.masterSchool']);
        $participantDetail = "{$eventSessionParticipant->masterParticipant->name} "
            .'('.optional($eventSessionParticipant->masterParticipant->masterSchool)->name.')';

        $rules = [
            // 'participants.*.master_participant_id' => 'required|exists:' . MasterParticipant::table() . ',id',
            'participants.*.master_participant_id' => [
                'required',
                'integer',
                'exists:'.MasterParticipant::table().',id',
                'distinct',
            ],
            'participants.*.ordering' => 'required|numeric|min:0|max:4',
        ];

        $attributes = [];
        foreach ($request->input('participants') ?? [] as $i => $participant) {
            if (array_key_exists('master_participant_id', $participant)) {
                $attributes["participants.$i.master_participant_id"]
                    = __('Atlet').' ke-'.($i + 1);
            }
            if (array_key_exists('ordering', $participant)) {
                $attributes["participants.$i.ordering"]
                    = __('Urutan').' ke-'.($i + 1);
            }
        }

        $this->customAttributes = collect($attributes)
            ->merge($this->customAttributes)
            ->toArray();

        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $participantWithPivot = [];
        foreach ($request->input('participants') ?? [] as $i => $participant) {
            $masterParticipantId = $request->input('participants.'.$i.'.master_participant_id');

            $participantWithPivot[$masterParticipantId] = [
                'ordering' => $request->input('participants.'.$i.'.ordering') ?? 1,
            ];
        }
        // dd($participantWithPivot);

        if (! $eventSessionParticipant->participantDetails()->sync($participantWithPivot)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '{$participantDetail}' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '{$participantDetail}' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', [
                'event' => $event->id,
            ])
            ->withSuccess("{$this->moduleName} '{$participantDetail}' telah diupdate.");
    }
}
