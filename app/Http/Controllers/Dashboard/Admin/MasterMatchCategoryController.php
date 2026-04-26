<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Libraries\FormFields;
use App\Models\EventRegistration;
use App\Models\EventStage;
use App\Models\MasterMatchCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterMatchCategoryController extends BaseController
{
    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = __('Kategori');
        $this->baseRouteName = 'dashboard.admin.master.category.';
        $this->baseViewPath = 'dashboard.admin.master.category.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'name' => __('Kategori'),
        ];
    }

    public function index()
    {
        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}",
            'categories' => MasterMatchCategory::withCount([
                'eventStages',
                'eventRegistrations',
                'events',
                'masterMatchTypes',
            ])->orderBy('name')->get(),
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    public function create(MasterMatchCategory $category)
    {
        $formFields = new FormFields($category);
        $formFields = $formFields->generateForm();

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'category' => $formFields,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }

    public function edit(MasterMatchCategory $category)
    {
        $formFields = new FormFields($category);
        $formFields = $formFields->generateForm();

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$category->name}",
            'category' => $formFields,
            'id' => $category->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:'.MasterMatchCategory::table().'|max:255',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        $validatedData['created_by'] = auth()->id();

        $category = MasterMatchCategory::create(array_filter($validatedData, 'trim'));
        if (! $category) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} Baru GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("{$this->moduleName} '$category->name' telah disimpan!");
    }

    public function update(Request $request, MasterMatchCategory $category)
    {
        $rules = [
            'name' => 'filled|unique:'.MasterMatchCategory::table().',name,'.$category->id.',id|max:255',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        $validatedData['updated_by'] = auth()->id();

        if (! $category->update($validatedData)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$category->name' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '$category->name' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} '$category->name' telah diupdate.");
    }

    public function destroy(Request $request, MasterMatchCategory $category)
    {
        if (! auth()->user()->hasRole('coach')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        if (! $category->delete()) {
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
            if (! MasterMatchCategory::where('name', $name)->exists()) {
                MasterMatchCategory::create([
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
                $matchCategory = MasterMatchCategory::find($id);
                if (! empty($matchCategory)) {
                    // ignore if having other relations!
                    if ($matchCategory->eventStages->count() > 0
                        || $matchCategory->eventRegistrations->count() > 0
                        || $matchCategory->events->count() > 0
                        || $matchCategory->masterMatchTypes->count() > 0
                    ) {
                        continue;
                    }

                    // if not, deleted!
                    $matchCategory->delete();
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

        // get matchCategories based on ids
        $matchCategories = MasterMatchCategory::withCount([
            'eventStages',
            'eventRegistrations',
            'events',
            'masterMatchTypes',
        ])
            ->whereIn('id', $request->ids)
            ->get();

        if (empty($matchCategories)) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        $matchCategoryOptions = [];
        if (! empty($matchCategories)) {
            foreach ($matchCategories as $matchCategory) {
                $matchCategoryOptions[$matchCategory->id] = "{$matchCategory->name} (AK: {$matchCategory->event_stages_count}, D: {$matchCategory->event_registrations_count}, K: {$matchCategory->events_count}, G: {$matchCategory->master_match_types_count})";
            }
        }

        $this->globalData = [
            'pageTitle' => "Merge {$this->moduleName}",
            'matchCategories' => $matchCategoryOptions,
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

        if (! $request->filled('match_category_ids') || ! $request->filled('destination_id')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        // get destionation matchCategory
        $matchCategoryDestination = MasterMatchCategory::select('id')
            ->where('id', $request->input('destination_id'))
            ->firstOrFail();

        $batchMerge = false;
        DB::beginTransaction();
        try {
            EventStage::whereIn('master_match_category_id', $request->input('match_category_ids'))
                ->update([
                    'master_match_category_id' => $matchCategoryDestination->id,
                ]);

            EventRegistration::whereIn('master_match_category_id', $request->input('match_category_ids'))
                ->update([
                    'master_match_category_id' => $matchCategoryDestination->id,
                ]);

            $eventCategories = DB::table('event_category')
                ->whereIn('master_match_category_id', $request->input('match_category_ids'))
                ->get();
            foreach ($eventCategories as $eventCategory) {
                try {
                    DB::table('event_category')
                        ->where([
                            'event_id' => $eventCategory->event_id,
                            'master_match_category_id' => $eventCategory->master_match_category_id,
                        ])
                        ->update([
                            'master_match_category_id' => $matchCategoryDestination->id,
                        ]);
                } catch (\Throwable $th) {
                    DB::table('event_category')
                        ->where([
                            'event_id' => $eventCategory->event_id,
                            'master_match_category_id' => $matchCategoryDestination->id,
                        ])
                        ->delete();
                }
            }

            $eventCategoryTypes = DB::table('event_category_type')
                ->whereIn('master_match_category_id', $request->input('match_category_ids'))
                ->get();
            foreach ($eventCategoryTypes as $eventCategoryType) {
                try {
                    DB::table('event_category_type')
                        ->where([
                            'event_id' => $eventCategoryType->event_id,
                            'master_match_type_id' => $eventCategoryType->master_match_type_id,
                            'master_match_category_id' => $eventCategoryType->master_match_category_id,
                        ])
                        ->update([
                            'master_match_category_id' => $matchCategoryDestination->id,
                        ]);
                } catch (\Throwable $th) {
                    DB::table('event_category_type')
                        ->where([
                            'event_id' => $eventCategoryType->event_id,
                            'master_match_type_id' => $eventCategoryType->master_match_type_id,
                            'master_match_category_id' => $matchCategoryDestination->id,
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
