<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Libraries\FormFields;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use App\Models\UserEducation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterSchoolController extends BaseController
{
    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = __('Sekolah');
        $this->baseRouteName = 'dashboard.admin.master.school.';
        $this->baseViewPath = 'dashboard.admin.master.school.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'name' => __('Sekolah'),
        ];
    }

    public function index()
    {
        $schools = MasterSchool::withCount([
            'masterParticipants',
            'userEducations',
        ])
            ->orderBy('name')
            ->get();
        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}",
            'schools' => $schools,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    public function create(MasterSchool $school)
    {
        $formFields = new FormFields($school);
        $formFields = $formFields->generateForm();

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'school' => $formFields,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }

    public function edit(MasterSchool $school)
    {
        $formFields = new FormFields($school);
        $formFields = $formFields->generateForm();

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$school->name}",
            'school' => $formFields,
            'id' => $school->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:'.MasterSchool::table().',name,NULL,NULL,deleted_at,NULL|max:255',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        $validatedData['created_by'] = auth()->id();

        $school = MasterSchool::create(array_filter($validatedData, 'trim'));
        if (! $school) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} Baru GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("{$this->moduleName} '$school->name' telah disimpan!");
    }

    public function update(Request $request, MasterSchool $school)
    {
        $rules = [
            'name' => 'filled|unique:'.MasterSchool::table().',name,'.$school->id.',id,deleted_at,NULL|max:255',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        $validatedData['updated_by'] = auth()->id();

        if (! $school->update($validatedData)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$school->name' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '$school->name' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} '$school->name' telah diupdate.");
    }

    public function destroy(Request $request, MasterSchool $school)
    {
        if (! auth()->user()->hasRole('coach')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        if (! $school->delete()) {
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
                $school = MasterSchool::find($id);
                if (! empty($school)) {
                    // ignore if having other relations!
                    if ($school->masterParticipants->count() > 0
                        || $school->userEducations->count() > 0
                    ) {
                        continue;
                    }

                    // if not, deleted!
                    $school->delete();
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
        $schools = MasterSchool::withCount([
            'masterParticipants',
            'userEducations',
        ])
            ->whereIn('id', $request->ids)
            ->get();

        if (empty($schools)) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        $schoolOptions = [];
        if (! empty($schools)) {
            foreach ($schools as $school) {
                $schoolOptions[$school->id] = "{$school->name} (P: {$school->master_participants_count}, M: {$school->user_educations_count})";
            }
        }

        $this->globalData = [
            'pageTitle' => "Merge {$this->moduleName}",
            'schools' => $schoolOptions,
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

        if (! $request->filled('school_ids') || ! $request->filled('destination_id')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        // get destionation school
        $schoolDestination = MasterSchool::select('id')->where('id', $request->input('destination_id'))->firstOrFail();

        $batchMerge = false;
        DB::beginTransaction();
        try {
            MasterParticipant::whereIn('master_school_id', $request->input('school_ids'))
                ->update([
                    'master_school_id' => $schoolDestination->id,
                ]);

            UserEducation::whereIn('master_school_id', $request->input('school_ids'))
                ->update([
                    'master_school_id' => $schoolDestination->id,
                ]);

            DB::commit();
            $batchMerge = true;
        } catch (\Throwable $th) {
            // throw $th;
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
