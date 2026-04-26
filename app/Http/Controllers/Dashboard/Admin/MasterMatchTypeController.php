<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Libraries\FormFields;
use App\Models\EventStage;
use App\Models\MasterMatchType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterMatchTypeController extends BaseController
{
    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = __('Gaya');
        $this->baseRouteName = 'dashboard.admin.master.type.';
        $this->baseViewPath = 'dashboard.admin.master.type.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'name' => __('Gaya'),
        ];
    }

    public function index()
    {
        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}",
            'types' => MasterMatchType::withCount([
                'eventStages',
                'eventRegistrations',
                'masterParticipants',
            ])
                ->orderBy('name')
                ->get(),
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    public function create(MasterMatchType $type)
    {
        $formFields = new FormFields($type);
        $formFields = $formFields->generateForm();

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'type' => $formFields,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }

    public function edit(MasterMatchType $type)
    {
        $formFields = new FormFields($type);
        $formFields = $formFields->generateForm();

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$type->name}",
            'type' => $formFields,
            'id' => $type->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:'.MasterMatchType::table().'|max:255',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        $validatedData['created_by'] = auth()->id();

        $type = MasterMatchType::create(array_filter($validatedData, 'trim'));
        if (! $type) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} Baru GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("{$this->moduleName} '$type->name' telah disimpan!");
    }

    public function update(Request $request, MasterMatchType $type)
    {
        $rules = [
            'name' => 'filled|unique:'.MasterMatchType::table().',name,'.$type->id.',id|max:255',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        $validatedData['updated_by'] = auth()->id();

        if (! $type->update($validatedData)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$type->name' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '$type->name' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} '$type->name' telah diupdate.");
    }

    public function destroy(Request $request, MasterMatchType $type)
    {
        if (! auth()->user()->hasRole('coach')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        if (! $type->delete()) {
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

    public function createBatch()
    {
        $this->globalData = [
            'pageTitle' => "Buat Batch {$this->moduleName} Baru",
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form-batch', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create-batch', $this->globalData);
    }

    public function storeBatch(Request $request)
    {
        $rules = [
            'names' => 'required',
        ];
        $request->validate($rules, $this->customMessages, $this->customAttributes);

        $names = array_filter(explode("\r\n", $request->input('names')), 'trim');
        foreach ($names as $name) {
            if (! MasterMatchType::where('name', $name)->exists()) {
                MasterMatchType::create([
                    'name' => $name,
                    'created_by' => auth()->id(),
                ]);
            }
        }

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("Batch {$this->moduleName} baru telah disimpan!");
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
                $matchType = MasterMatchType::find($id);
                if (! empty($matchType)) {
                    // ignore if having other relations!
                    if ($matchType->eventStages->count() > 0
                        || $matchType->eventRegistrations->count() > 0
                        || $matchType->masterParticipants->count() > 0
                    ) {
                        continue;
                    }

                    // if not, deleted!
                    $matchType->delete();
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

        // get matchTypes based on ids
        $matchTypes = MasterMatchType::withCount([
            'eventStages',
            'eventRegistrations',
            'masterParticipants',
        ])
            ->whereIn('id', $request->ids)
            ->get();

        if (empty($matchTypes)) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        $matchTypeOptions = [];
        if (! empty($matchTypes)) {
            foreach ($matchTypes as $matchType) {
                $matchTypeOptions[$matchType->id] = "{$matchType->name} (K: {$matchType->event_stages_count}, D: {$matchType->event_registrations_count}, P: {$matchType->master_participants_count})";
            }
        }

        $this->globalData = [
            'pageTitle' => "Merge {$this->moduleName}",
            'matchTypes' => $matchTypeOptions,
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

        if (! $request->filled('match_type_ids') || ! $request->filled('destination_id')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        // get destionation matchType
        $matchTypeDestination = MasterMatchType::select('id')
            ->where('id', $request->input('destination_id'))
            ->firstOrFail();

        $batchMerge = false;
        DB::beginTransaction();
        try {
            EventStage::whereIn('master_match_type_id', $request->input('match_type_ids'))
                ->update([
                    'master_match_type_id' => $matchTypeDestination->id,
                ]);

            $eventRegistrationStyles = DB::table('event_registration_style')
                ->whereIn('master_match_type_id', $request->input('match_type_ids'))
                ->get();
            foreach ($eventRegistrationStyles as $eventRegistrationStyle) {
                try {
                    DB::table('event_registration_style')
                        ->where([
                            'event_registration_id' => $eventRegistrationStyle->event_registration_id,
                            'master_match_type_id' => $eventRegistrationStyle->master_match_type_id,
                        ])
                        ->update([
                            'master_match_type_id' => $matchTypeDestination->id,
                        ]);
                } catch (\Throwable $th) {
                    DB::table('event_registration_style')
                        ->where([
                            'event_registration_id' => $eventRegistrationStyle->event_registration_id,
                            'master_match_type_id' => $matchTypeDestination->id,
                        ])
                        ->delete();
                }
            }

            $masterParticipantStyles = DB::table('master_participant_style')
                ->whereIn('master_match_type_id', $request->input('match_type_ids'))
                ->get();
            foreach ($masterParticipantStyles as $masterParticipantStyle) {
                try {
                    DB::table('master_participant_style')
                        ->where([
                            'master_participant_id' => $masterParticipantStyle->master_participant_id,
                            'master_match_type_id' => $masterParticipantStyle->master_match_type_id,
                        ])
                        ->update([
                            'master_match_type_id' => $matchTypeDestination->id,
                        ]);
                } catch (\Throwable $th) {
                    DB::table('master_participant_style')
                        ->where([
                            'master_participant_id' => $masterParticipantStyle->master_participant_id,
                            'master_match_type_id' => $matchTypeDestination->id,
                        ])
                        ->delete();
                }
            }

            $eventTypes = DB::table('event_type')
                ->whereIn('master_match_type_id', $request->input('match_type_ids'))
                ->get();
            foreach ($eventTypes as $eventType) {
                try {
                    DB::table('event_type')
                        ->where([
                            'event_id' => $eventType->event_id,
                            'master_match_type_id' => $eventType->master_match_type_id,
                        ])
                        ->update([
                            'master_match_type_id' => $matchTypeDestination->id,
                        ]);
                } catch (\Throwable $th) {
                    DB::table('event_type')
                        ->where([
                            'event_id' => $eventType->event_id,
                            'master_match_type_id' => $matchTypeDestination->id,
                        ])
                        ->delete();
                }
            }

            $eventCategoryTypes = DB::table('event_category_type')
                ->whereIn('master_match_type_id', $request->input('match_type_ids'))
                ->get();
            foreach ($eventCategoryTypes as $eventCategoryType) {
                try {
                    DB::table('event_category_type')
                        ->where([
                            'event_id' => $eventCategoryType->event_id,
                            'master_match_category_id' => $eventCategoryType->master_match_category_id,
                            'master_match_type_id' => $eventCategoryType->master_match_type_id,
                        ])
                        ->update([
                            'master_match_type_id' => $matchTypeDestination->id,
                        ]);
                } catch (\Throwable $th) {
                    DB::table('event_category_type')
                        ->where([
                            'event_id' => $eventCategoryType->event_id,
                            'master_match_category_id' => $eventCategoryType->master_match_category_id,
                            'master_match_type_id' => $matchTypeDestination->id,
                        ])
                        ->delete();
                }
            }

            DB::commit();
            $batchMerge = true;
        } catch (\Throwable $th) {
            // throw $th;
            logger()->info($th->getMessage());
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
