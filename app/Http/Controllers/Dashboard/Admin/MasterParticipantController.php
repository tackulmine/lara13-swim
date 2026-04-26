<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Libraries\FormFields;
use App\Models\EventRegistration;
use App\Models\EventSessionParticipant;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterParticipantController extends BaseController
{
    public $schoolOptions;

    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Peserta';
        $this->baseRouteName = 'dashboard.admin.master.participant.';
        $this->baseViewPath = 'dashboard.admin.master.participant.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'name' => __('Atlet'),
            'gender' => __('Gender'),
            'master_school_id' => __('Sekolah'),
            // 'address' => 'Alamat',
            // 'location' => 'Lokasi',
            // 'birth_date' => 'Tanggal Lahir',
            'birth_year' => __('Tahun Lahir'),
        ];
    }

    protected function generateOptions()
    {
        $this->schoolOptions = MasterSchool::orderBy('name')
            ->pluck('name', 'id')
            ->prepend('-- '.__('Sekolah').' --', '');

        $this->globalData = [
            'schoolOptions' => $this->schoolOptions,
        ] + $this->globalData;
    }

    public function index()
    {
        $participants = MasterParticipant::with('masterSchool')
            ->withCount([
                'eventRegistrations',
                'eventSessionParticipants',
                'styles',
                'eventSessionParticipantDetails',
            ])
            ->orderBy('name', 'asc')
            ->get();
        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}",
            'participants' => $participants,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    public function create(MasterParticipant $participant)
    {
        $this->generateOptions();

        $formFields = new FormFields($participant);
        $formFields = $formFields->generateForm();

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'participant' => $formFields,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }

    public function edit(MasterParticipant $participant)
    {
        $this->generateOptions();

        $formFields = new FormFields($participant);
        $formFields = $formFields->generateForm();

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$participant->name}",
            'participant' => $formFields,
            'id' => $participant->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }

    public function store(Request $request)
    {
        $rules = [
            // 'name' => 'required|max:255|unique:'.MasterParticipant::table().',name,NULL,NULL,deleted_at,NULL',
            'name' => [
                'filled',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $checkDuplicate = MasterParticipant::where([
                        'name' => $value,
                        'master_school_id' => $request->master_school_id,
                        'gender' => $request->gender,
                        'birth_year' => $request->birth_year,
                    ])
                        ->exists();

                    if ($checkDuplicate) {
                        $fail($attribute.' sudah ada sebelumnya.');
                    }
                },
            ],
            'master_school_id' => 'nullable|exists:'.MasterSchool::table().',id',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        $validatedData['created_by'] = auth()->id();
        $validatedData['birth_date'] = $request->birth_date ? Carbon::createFromFormat('d/M/Y', $request->birth_date) : null;

        $data = array_filter($validatedData + $request->all());

        $participant = MasterParticipant::create($data);
        if (! $participant) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} Baru GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("{$this->moduleName} '$participant->name' telah disimpan!");
    }

    public function update(Request $request, MasterParticipant $participant)
    {
        $rules = [
            // 'name' => 'filled|max:255|unique:'.MasterParticipant::table().',name,'.$participant->id.',id,deleted_at,NULL',
            'name' => [
                'filled',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $participant) {
                    $checkDuplicate = MasterParticipant::where([
                        'name' => $value,
                        'master_school_id' => $request->master_school_id,
                        'gender' => $request->gender,
                        'birth_year' => $request->birth_year,
                    ])
                        ->where('id', '<>', $participant->id)
                        ->exists();

                    if ($checkDuplicate) {
                        $fail($attribute.' sudah ada sebelumnya.');
                    }
                },
            ],
            'master_school_id' => 'nullable|exists:'.MasterSchool::table().',id',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        $validatedData['updated_by'] = auth()->id();
        $validatedData['birth_date'] = $request->birth_date ? Carbon::createFromFormat('d/M/Y', $request->birth_date) : null;

        $data = $validatedData + $request->all();

        if (! $participant->update($data)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$participant->name' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '$participant->name' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} '$participant->name' telah diupdate.");
    }

    public function destroy(Request $request, MasterParticipant $participant)
    {
        if (! auth()->user()->hasRole('coach')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        if (! $participant->delete()) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL dihapus!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL dihapus!"]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah dihapus."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah dihapus.");
    }

    public function destroyBatch(Request $request)
    {
        if (! auth()->user()->hasRole('coach')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        if (! $request->filled('ids')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        $batchDestroy = false;
        $total = count($request->ids);
        $deletedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($request->ids as $id) {
                $participant = MasterParticipant::find($id);
                if (! empty($participant)) {
                    // ignore if having other relations!
                    if (
                        $participant->eventRegistrations->count() > 0
                        || $participant->eventSessionParticipants->count() > 0
                        || $participant->styles->count() > 0
                    ) {
                        continue;
                    }

                    // if not, deleted!
                    $participant->delete();
                    $deletedCount++;
                }
            }

            DB::commit();
            $batchDestroy = true;
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
        }

        if (! $batchDestroy) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL dihapus!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL dihapus!"]);
        }

        $msg = "{$this->moduleName} telah dihapus.";

        if ($deletedCount != $total) {
            $msg = "{$this->moduleName} telah dihapus sebagian.";
        }

        if ($request->ajax()) {
            return response()->json(['message' => $msg], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess($msg);
    }

    public function merger(Request $request)
    {
        if (! auth()->user()->hasRole('coach')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        if (! $request->filled('ids')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        // get schools based on ids
        $participants = MasterParticipant::with('masterSchool')
            ->withCount([
                'eventRegistrations',
                'eventSessionParticipants',
                'styles',
                'eventSessionParticipantDetails',
            ])
            ->whereIn('id', $request->ids)
            ->get();

        if (empty($participants)) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        $participantOptions = [];
        if (! empty($participants)) {
            foreach ($participants as $participant) {
                $participantOptions[$participant->id] = "{$participant->name} (".parseGenderAbbr($participant->gender)
                    .', '
                    .($participant->birth_year ?? '-')
                    .', '
                    .optional($participant->masterSchool)->name
                    .") ~ (D: {$participant->event_registrations_count}, P: {$participant->event_session_participants_count}, G: {$participant->styles_count}, E: {$participant->event_session_participant_details_count})";
            }
        }

        $this->globalData = [
            'pageTitle' => "Merge {$this->moduleName}",
            'participants' => $participantOptions,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form-merge', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit-merge', $this->globalData);
    }

    public function updateMerger(Request $request)
    {
        if (! auth()->user()->hasRole('coach')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        if (! $request->filled('participant_ids') || ! $request->filled('destination_id')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        // get destionation school
        $participantDestination = MasterParticipant::select('id')->where('id', $request->input('destination_id'))->firstOrFail();

        $batchMerge = false;
        DB::beginTransaction();
        try {
            EventRegistration::whereIn('master_participant_id', $request->input('participant_ids'))
                ->update([
                    'master_participant_id' => $participantDestination->id,
                ]);

            EventSessionParticipant::whereIn('master_participant_id', $request->input('participant_ids'))
                ->update([
                    'master_participant_id' => $participantDestination->id,
                ]);

            $masterParticipantStyles = DB::table('master_participant_style')
                ->whereIn('master_participant_id', $request->input('participant_ids'))
                // ->update([
                //     'master_participant_id' => $participantDestination->id,
                // ]);
                ->get();

            foreach ($masterParticipantStyles as $masterParticipantStyle) {
                try {
                    DB::table('master_participant_style')
                        ->where([
                            'master_match_type_id' => $masterParticipantStyle->master_match_type_id,
                            'master_participant_id' => $masterParticipantStyle->master_participant_id,
                        ])
                        ->update([
                            'master_participant_id' => $participantDestination->id,
                        ]);
                } catch (\Throwable $th) {
                    DB::table('master_participant_style')
                        ->where([
                            'master_match_type_id' => $masterParticipantStyle->master_match_type_id,
                            'master_participant_id' => $participantDestination->id,
                        ])
                        ->delete();
                }
            }

            $eventSessionParticipantDetails = DB::table('event_session_participant_detail')
                ->whereIn('master_participant_id', $request->input('participant_ids'))
                ->get();

            foreach ($eventSessionParticipantDetails as $eventSessionParticipantDetail) {
                try {
                    DB::table('event_session_participant_detail')
                        ->where([
                            'event_session_participant_id' => $eventSessionParticipantDetail->event_session_participant_id,
                            'master_participant_id' => $eventSessionParticipantDetail->master_participant_id,
                        ])
                        ->update([
                            'master_participant_id' => $participantDestination->id,
                        ]);
                } catch (\Throwable $th) {
                    DB::table('event_session_participant_detail')
                        ->where([
                            'event_session_participant_id' => $eventSessionParticipantDetail->event_session_participant_id,
                            'master_participant_id' => $participantDestination->id,
                        ])
                        ->delete();
                }
            }

            DB::commit();
            $batchMerge = true;
        } catch (\Throwable $th) {
            // throw $th;
            logger()->debug($th->getMessage());
            DB::rollback();
        }

        if (! $batchMerge) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL digabungkan!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL digabungkan!"]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah digabungkan."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah digabungkan.");
    }
}
