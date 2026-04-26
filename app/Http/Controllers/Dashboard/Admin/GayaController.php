<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Libraries\FormFields;
use App\Models\MasterGaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GayaController extends BaseController
{
    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = __('Gaya');
        $this->baseRouteName = 'dashboard.admin.gaya.';
        $this->baseViewPath = 'dashboard.admin.gaya.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'name' => 'Nama',
        ];
    }

    public function index(Request $request)
    {
        $gayas = MasterGaya::orderBy('name')
            ->withCount('userMemberGayaLimits', 'userMemberLimits')
            ->when($request->filled('trashed'), function ($q) {
                $q->onlyTrashed();
            })
            ->get();

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}".($request->filled('trashed') ? ' Non Aktif' : ' Aktif'),
            'gayas' => $gayas,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    public function create(MasterGaya $gaya)
    {
        $formFields = new FormFields($gaya);
        $formFields = $formFields->generateForm();
        // dd($formFields);

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'gaya' => $formFields,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }

    public function store(Request $request)
    {
        $gayaTable = (new MasterGaya)->getTable();
        $rules = [
            'name' => 'required|unique:'.$gayaTable.'|min:2|max:100',
        ];

        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        // $validatedData['created_by'] = auth()->id();

        $gaya = MasterGaya::create($request->only('name'));
        if (! $gaya) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} Baru GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("{$this->moduleName} '{$gaya->name}' telah disimpan!");
    }

    public function edit(MasterGaya $gaya)
    {
        $formFields = new FormFields($gaya);
        $formFields = $formFields->generateForm();
        // dd($formFields);

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$gaya->name}",
            'gaya' => $formFields,
            'id' => $gaya->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }

    public function update(Request $request, MasterGaya $gaya)
    {
        $gayaTable = (new MasterGaya)->getTable();
        $rules = [
            'name' => 'filled|unique:'.$gayaTable.',name,'.$gaya->id.',id|min:2|max:100',
        ];

        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        // $validatedData['updated_by'] = auth()->id();

        // IF FAILED
        if (! $gaya->update($request->only('name'))) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '{$gaya->name}' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '{$gaya->name}' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} '{$gaya->name}' telah diupdate.");
    }

    public function destroy(Request $request, MasterGaya $gaya)
    {
        if (! $gaya->delete()) {
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
        if (! $request->filled('ids')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        $batchDestroy = false;
        DB::beginTransaction();
        try {
            foreach ($request->ids as $id) {
                optional(MasterGaya::find($id))->delete();
            }

            DB::commit();
            $batchDestroy = true;
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
        }

        if (! $batchDestroy) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL dinon-aktifkan!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL dinon-aktifkan!"]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah dinon-aktifkan."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah dinon-aktifkan.");
    }

    public function restoreBatch(Request $request)
    {
        if (! $request->filled('ids')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        $batchRestore = false;
        DB::beginTransaction();
        try {
            foreach ($request->ids as $id) {
                optional(MasterGaya::onlyTrashed()->find($id))->restore();
            }

            DB::commit();
            $batchRestore = true;
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
        }

        if (! $batchRestore) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL diaktifkan!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL diaktifkan!"]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah diaktifkan."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah diaktifkan.");
    }
}
