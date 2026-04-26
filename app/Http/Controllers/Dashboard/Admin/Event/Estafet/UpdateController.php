<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Estafet;

use App\Models\Event;
use App\Models\EventSessionParticipant;
use App\Models\MasterParticipant;
use App\Rules\ValidateArrayKeys;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UpdateController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->generateFormAttributes();
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

        // $eventSessionParticipant->load(['masterParticipant', 'masterParticipant.masterSchool']);
        // $participantDetail = "{$eventSessionParticipant->masterParticipant->name} "
        //     .'('.optional($eventSessionParticipant->masterParticipant->masterSchool)->name.')';

        $rules = [
            // Validasi KEY array pertama (harus ada di tabel 'participants')
            'participants' => 'required|array',
            // 'participants' => ['required', 'array', new ValidateArrayKeys(MasterParticipant::table(), 'id')],
            // 'participants.*.*.master_participant_id' => [
            //     'required',
            //     'integer',
            //     'exists:' . MasterParticipant::table() . ',id',
            //     'distinct',
            // ],
            'participants.*.*.ordering' => 'required|numeric|min:0|max:4',
        ];

        $attributes = [];
        foreach ($request->input('participants') ?? [] as $participantId => $subArray) {
            foreach ($subArray as $i => $participant) {
                $eventSessionParticipant = EventSessionParticipant::find($participantId);

                if (array_key_exists('master_participant_id', $participant)) {
                    $attributes["participants.$participantId.$i.master_participant_id"]
                        = 'Peserta Estafet: '.$eventSessionParticipant->masterParticipant->masterSchool->name.', '.__('Atlet').' ke-'.($i + 1);
                }
                if (array_key_exists('ordering', $participant)) {
                    $attributes["participants.$participantId.$i.ordering"]
                        = 'Peserta Estafet: '.$eventSessionParticipant->masterParticipant->masterSchool->name.', '.__('Urutan').' ke-'.($i + 1);
                }
            }
        }

        $this->customAttributes = collect($attributes)
            ->merge($this->customAttributes)
            ->toArray();

        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        try {
            DB::beginTransaction();
            $failed = false;
            foreach ($request->input('participants') ?? [] as $participantId => $subArray) {
                if (empty($participantId)) {
                    continue;
                }

                $participantWithPivot = [];
                foreach ($subArray as $i => $participant) {
                    $masterParticipantId = $request->input('participants.'.$participantId.'.'.$i.'.master_participant_id');
                    if (empty($masterParticipantId)) {
                        continue;
                    }

                    $participantWithPivot[$masterParticipantId] = [
                        'ordering' => $request->input('participants.'.$participantId.'.'.$i.'.ordering') ?? 1,
                    ];
                }

                $eventSessionParticipant = EventSessionParticipant::find($participantId);
                // dd($participantWithPivot);
                $eventSessionParticipant->participantDetails()->sync($participantWithPivot);
            }

            DB::commit();

            $msg = "Data {$this->moduleName} berhasil disimpan!";

            if ($request->action === 'continue') {
                return back()
                    ->withSuccess($msg);
            }

            return redirect()
                ->route($this->parentRouteName.'index', array_merge([
                    'event' => $event->id,
                ], getQueryParams()))
                ->withSuccess($msg);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan data: '.$e->getMessage()]);
        }
    }
}
