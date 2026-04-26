<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Libraries\FormFields;
use App\Models\MasterGaya;
use App\Models\MasterMemberClass;
use App\Models\User;
use App\Models\UserMemberGayaLimit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MemberGayaLimitController extends BaseController
{
    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Target Limit Atlit';
        $this->baseRouteName = 'dashboard.admin.member-gaya-limit.';
        $this->baseViewPath = 'dashboard.admin.member-gaya-limit.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'master_gaya' => 'Jenis Gaya',
            'user_id' => 'Member',
        ];
    }

    protected function generateOptions()
    {
        $members = User::member()->orderBy('name')->get(['id', 'username', 'name']);
        $memberOptions = [];
        foreach ($members as $member) {
            $memberOptions[$member->id] = "$member->name ($member->username)";
        }

        $this->globalData = [
            'memberOptions' => collect($memberOptions)->prepend('-- Pilih --', ''),
            'masterGayaOptions' => MasterGaya::orderByRaw('CAST(`name` AS UNSIGNED) ASC')->pluck('name', 'name')->prepend('-- Pilih --', ''),
            'masterClassOptions' => MasterMemberClass::orderBy('name')->pluck('name', 'slug')->prepend('-- Pilih --', ''),
        ] + $this->globalData;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $memberGayaLimitTable = (new UserMemberGayaLimit)->getTable();
        $limits = UserMemberGayaLimit::with('user', 'gaya')
            ->when(! $request->filled('periode_start') || ! $request->filled('periode_end'), function ($query) use ($memberGayaLimitTable) {
                $query->where($memberGayaLimitTable.'.periode_year', now()->year)
                    ->where($memberGayaLimitTable.'.periode_month', now()->month);
            })
            ->when($request->filled('periode_start') && $request->filled('periode_end'), function ($query) use ($request, $memberGayaLimitTable) {
                $periodeStart = explode('-', $request->periode_start);
                $periodeEnd = explode('-', $request->periode_end);
                $query->where(function ($query) use ($memberGayaLimitTable, $periodeStart) {
                    $query->where($memberGayaLimitTable.'.periode_year', '>=', $periodeStart[1])
                        ->where($memberGayaLimitTable.'.periode_month', '>=', $periodeStart[0]);
                })->where(function ($query) use ($memberGayaLimitTable, $periodeEnd) {
                    $query->where($memberGayaLimitTable.'.periode_year', '<=', $periodeEnd[1])
                        ->where($memberGayaLimitTable.'.periode_month', '<=', $periodeEnd[0]);
                });
            })
            ->join('users', $memberGayaLimitTable.'.user_id', (new User)->getTable().'.id')
            ->whereHas('user', function ($q) {
                $q->select('id');
            })
            ->orderBy((new User)->getTable().'.username')
            ->orderBy($memberGayaLimitTable.'.periode_year')
            ->orderBy($memberGayaLimitTable.'.periode_month')
            ->get([$memberGayaLimitTable.'.*']);

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}",
            'limits' => $limits,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(UserMemberGayaLimit $memberGayaLimit)
    {
        $this->generateOptions();

        $memberGayaLimit->load('user', 'gaya');

        $formFields = new FormFields($memberGayaLimit);
        $formFields = $formFields->generateForm();

        $formFields->periode_month_year = old('periode_month_year', $formFields->periode_month
            ? $formFields->periode_month.'-'.$formFields->periode_year
            : now()->format('m-Y'));
        $formFields->master_gaya = old('master_gaya');

        $members = User::has('userMember')
            ->with('profile', 'userMember', 'userMember.type', 'educations', 'educations.school')
            ->where('id', '<>', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'memberGayaLimit' => $formFields,
            'members' => $members,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $rules = [
            'periode_month_year' => 'required',
            'master_gaya' => 'required',
        ];

        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $data = array_filter($validatedData + $request->all());
        $dataColl = collect($data);

        $periodeMonthYearParsed = explode('-', $request->input('periode_month_year'));
        $dataColl->put('periode_month', $periodeMonthYearParsed[0]);
        $dataColl->put('periode_year', $periodeMonthYearParsed[1]);

        DB::beginTransaction();
        try {
            $masterGaya = MasterGaya::firstOrCreate([
                'name' => $request->master_gaya,
            ]);
            $dataColl->put('master_gaya_id', $masterGaya->id);
            $dataColl->put('point', parsePointToInt($request->point_text));

            $memberGayaLimit = UserMemberGayaLimit::updateOrCreate(
                $dataColl->only('user_id', 'master_gaya_id', 'periode_month', 'periode_year')->all(),
                $dataColl->only('point', 'point_text')->all(),
            );
            if (! $memberGayaLimit) {
                DB::rollback();

                return back()
                    ->withInput()
                    ->withErrors(["{$this->moduleName} baru GAGAL disimpan!"]);
            }

            DB::commit();

            return redirect()
                ->route($this->baseRouteName.'index')
                ->withSuccess("{$this->moduleName} baru telah disimpan!");
        } catch (\Throwable $th) {
            DB::rollback();

            return back()
                ->withInput()
                ->withErrors([$th->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserMemberGayaLimit  $memberGayaLimit
     * @return Response
     */
    public function edit(UserMemberGayaLimit $memberGayaLimit)
    {
        $this->generateOptions();

        $memberGayaLimit->load('user', 'gaya', 'user.educations', 'user.educations.school');

        $formFields = new FormFields($memberGayaLimit);
        $formFields = $formFields->generateForm();

        $formFields->periode_month_year = old('periode_month_year', $formFields->periode_month
            ? $formFields->periode_month.'-'.$formFields->periode_year
            : now()->format('m-Y'));
        $formFields->master_gaya = old('master_gaya', optional($memberGayaLimit->gaya)->name);
        $formFields->user_name = optional($memberGayaLimit->user)->name;
        $formFields->master_school = optional(optional(optional(optional($memberGayaLimit->user)->educations)->first())->school)->name;

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$memberGayaLimit->user->name}",
            'memberGayaLimit' => $formFields,
            'id' => $memberGayaLimit->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form-edit', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\UserMemberGayaLimit  $memberGayaLimit
     * @return Response
     */
    public function update(Request $request, UserMemberGayaLimit $memberGayaLimit)
    {
        $memberGayaLimit->load('user', 'gaya');

        $rules = [
            'periode_month_year' => 'filled',
            'master_gaya' => 'filled',
        ];

        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $data = array_filter($validatedData + $request->all());
        $dataColl = collect($data);

        $periodeMonthYearParsed = explode('-', $request->input('periode_month_year'));
        $dataColl->put('periode_month', $periodeMonthYearParsed[0]);
        $dataColl->put('periode_year', $periodeMonthYearParsed[1]);
        $dataColl->put('point', parsePointToInt($request->input('point_text')));
        if (! MasterGaya::find($request->master_gaya)) {
            $masterGaya = MasterGaya::firstOrCreate([
                'name' => $request->master_gaya,
            ]);
            $dataColl->put('master_gaya_id', $masterGaya->id);
        }

        DB::beginTransaction();
        try {
            // code...
            // IF FAILED
            if (! $memberGayaLimit->update($dataColl->all())) {
                DB::rollback();

                return back()
                    ->withInput()
                    ->withErrors(["{$this->moduleName} '{$memberGayaLimit->user->name}' GAGAL diupdate!"]);
            }

            DB::commit();

            if ($request->action === 'continue') {
                return back()
                    ->withSuccess("{$this->moduleName} '{$memberGayaLimit->user->name}' telah diupdate.");
            }

            return redirect()
                ->route($this->baseRouteName.'index', getQueryParams())
                ->withSuccess("{$this->moduleName} '{$memberGayaLimit->user->name}' telah diupdate.");
        } catch (\Throwable $th) {
            DB::rollback();

            return back()
                ->withInput()
                ->withErrors([$th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserMemberGayaLimit  $memberGayaLimit
     * @return Response
     */
    public function destroy(Request $request, UserMemberGayaLimit $memberGayaLimit)
    {
        if (! $memberGayaLimit->delete()) {
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
                optional(UserMemberGayaLimit::find($id))->delete();
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

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah dihapus."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah dihapus.");
    }

    /**
     * Show the form for creating batch new resource.
     *
     * @return Response
     */
    public function createBatch(UserMemberGayaLimit $memberGayaLimit)
    {
        $this->generateOptions();

        $memberGayaLimit->load('user', 'gaya');

        $formFields = new FormFields($memberGayaLimit);
        $formFields = $formFields->generateForm();

        $formFields->periode_month_year = old('periode_month_year', $formFields->periode_month
            ? $formFields->periode_month.'-'.$formFields->periode_year
            : now()->format('m-Y'));
        $formFields->master_gaya = old('master_gaya');

        $members = User::has('userMember')
            ->with('profile', 'userMember', 'userMember.type', 'educations', 'educations.school')
            ->where('id', '<>', 1)
            ->orderBy('username')
            ->get(['id', 'username', 'name']);

        $this->globalData = [
            'pageTitle' => "Buat Batch {$this->moduleName}",
            'memberGayaLimit' => $formFields,
            'members' => $members,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form-batch', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create-batch', $this->globalData);
    }

    /**
     * Store a newly created batch resource in storage.
     *
     * @return Response
     */
    public function storeBatch(Request $request)
    {
        $rules = [
            'periode_month_year' => 'required',
            'master_gaya' => 'required',
        ];

        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $data = array_filter($validatedData + $request->all());
        $dataColl = collect($data);

        $periodeMonthYearParsed = explode('-', $request->input('periode_month_year'));
        $dataColl->put('periode_month', $periodeMonthYearParsed[0]);
        $dataColl->put('periode_year', $periodeMonthYearParsed[1]);

        DB::beginTransaction();
        try {
            $masterGaya = MasterGaya::firstOrCreate([
                'name' => $request->master_gaya,
            ]);
            $dataColl->put('master_gaya_id', $masterGaya->id);

            foreach ($request->input('point_text') as $userId => $value) {
                if (empty($value)) {
                    continue;
                }

                $dataColl->put('user_id', $userId);
                $dataColl->put('point_text', $value);
                $dataColl->put('point', parsePointToInt($value));

                $memberGayaLimit = UserMemberGayaLimit::updateOrCreate(
                    $dataColl->only('user_id', 'master_gaya_id', 'periode_month', 'periode_year')->all(),
                    $dataColl->only('point', 'point_text')->all(),
                );
                if (! $memberGayaLimit) {
                    DB::rollback();

                    return back()
                        ->withInput()
                        ->withErrors(["Batch {$this->moduleName} baru GAGAL disimpan!"]);
                }
            }

            DB::commit();

            return redirect()
                ->route($this->baseRouteName.'index')
                ->withSuccess("Batch {$this->moduleName} baru telah disimpan!");
        } catch (\Throwable $th) {
            DB::rollback();

            return back()
                ->withInput()
                ->withErrors([$th->getMessage()]);
        }
    }
}
