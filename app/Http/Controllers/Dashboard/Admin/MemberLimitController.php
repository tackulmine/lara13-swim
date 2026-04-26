<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Libraries\FormFields;
use App\Models\MasterGaya;
use App\Models\MasterMemberClass;
use App\Models\User;
use App\Models\UserMemberLimit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MemberLimitController extends BaseController
{
    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Limit Atlit';
        $this->baseRouteName = 'dashboard.admin.member-limit.';
        $this->baseViewPath = 'dashboard.admin.member-limit.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'periode_week' => 'Minggu Ke-',
            'periode_month_year' => 'Bulan',
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
        $memberLimitTable = (new UserMemberLimit)->getTable();
        $limits = UserMemberLimit::with('user', 'gaya')
            ->when(! $request->filled('periode_start') || ! $request->filled('periode_end'), function ($query) use ($memberLimitTable) {
                $query->where($memberLimitTable.'.periode_year', now()->year)
                    ->where($memberLimitTable.'.periode_month', now()->month);
            })
            ->when($request->filled('periode_start') && $request->filled('periode_end'), function ($query) use ($request, $memberLimitTable) {
                $periodeStart = explode('-', $request->periode_start);
                $periodeEnd = explode('-', $request->periode_end);
                $query->where(function ($query) use ($memberLimitTable, $periodeStart) {
                    $query->where($memberLimitTable.'.periode_year', '>=', $periodeStart[1])
                        ->where($memberLimitTable.'.periode_month', '>=', $periodeStart[0]);
                })->where(function ($query) use ($memberLimitTable, $periodeEnd) {
                    $query->where($memberLimitTable.'.periode_year', '<=', $periodeEnd[1])
                        ->where($memberLimitTable.'.periode_month', '<=', $periodeEnd[0]);
                });
            })
            ->join('users', $memberLimitTable.'.user_id', (new User)->getTable().'.id')
            ->whereHas('user', function ($q) {
                $q->select('id');
            })
            ->orderBy((new User)->getTable().'.username')
            ->orderBy($memberLimitTable.'.periode_year')
            ->orderBy($memberLimitTable.'.periode_month')
            ->orderBy($memberLimitTable.'.periode_week')
            ->get($memberLimitTable.'.*');

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
    public function create(UserMemberLimit $memberLimit)
    {
        $this->generateOptions();

        $memberLimit->load('user', 'gaya');

        $formFields = new FormFields($memberLimit);
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
            'memberLimit' => $formFields,
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
            'periode_week' => 'required|integer|min:1|max:4',
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

            $memberLimit = UserMemberLimit::updateOrCreate(
                $dataColl->only('user_id', 'master_gaya_id', 'periode_week', 'periode_month', 'periode_year')->all(),
                $dataColl->only('point', 'point_text')->all(),
            );
            if (! $memberLimit) {
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
     * @param  \App\UserMemberLimit  $memberLimit
     * @return Response
     */
    public function edit(UserMemberLimit $memberLimit)
    {
        $this->generateOptions();

        $memberLimit->load('user', 'gaya', 'user.educations', 'user.educations.school');

        $formFields = new FormFields($memberLimit);
        $formFields = $formFields->generateForm();

        $formFields->periode_month_year = old('periode_month_year', $formFields->periode_month
            ? $formFields->periode_month.'-'.$formFields->periode_year
            : now()->format('m-Y'));
        $formFields->master_gaya = old('master_gaya', optional($memberLimit->gaya)->name);
        $formFields->user_name = optional($memberLimit->user)->name;
        $formFields->master_school = optional(optional(optional(optional($memberLimit->user)->educations)->first())->school)->name;

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$memberLimit->user->name}",
            'memberLimit' => $formFields,
            'id' => $memberLimit->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form-edit', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\UserMemberLimit  $memberLimit
     * @return Response
     */
    public function update(Request $request, UserMemberLimit $memberLimit)
    {
        $memberLimit->load('user', 'gaya');

        $rules = [
            'periode_month_year' => 'filled',
            'periode_week' => 'filled|integer|min:1|max:4',
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
            if (! $memberLimit->update($dataColl->all())) {
                DB::rollback();

                return back()
                    ->withInput()
                    ->withErrors(["{$this->moduleName} '{$memberLimit->user->name}' GAGAL diupdate!"]);
            }

            DB::commit();

            if ($request->action === 'continue') {
                return back()
                    ->withSuccess("{$this->moduleName} '{$memberLimit->user->name}' telah diupdate.");
            }

            return redirect()
                ->route($this->baseRouteName.'index', getQueryParams())
                ->withSuccess("{$this->moduleName} '{$memberLimit->user->name}' telah diupdate.");
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
     * @param  \App\UserMemberLimit  $memberLimit
     * @return Response
     */
    public function destroy(Request $request, UserMemberLimit $memberLimit)
    {
        if (! $memberLimit->delete()) {
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
                optional(UserMemberLimit::find($id))->delete();
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
    public function createBatch(UserMemberLimit $memberLimit)
    {
        $this->generateOptions();

        $memberLimit->load('user', 'gaya');

        $formFields = new FormFields($memberLimit);
        $formFields = $formFields->generateForm();

        $formFields->periode_month_year = old('periode_month_year', $formFields->periode_month
            ? $formFields->periode_month.'-'.$formFields->periode_year
            : now()->format('m-Y'));
        $formFields->master_gaya = old('master_gaya');

        $members = User::has('userMember')
            ->with('profile', 'userMember', 'userMember.type', 'userMember.class', 'educations', 'educations.school')
            ->where('id', '<>', 1)
            ->orderBy('username')
            ->get(['id', 'username', 'name']);

        $this->globalData = [
            'pageTitle' => "Buat Batch {$this->moduleName}",
            'memberLimit' => $formFields,
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
            // 'periode_week' => 'required|integer|min:1|max:4',
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

            foreach ($request->input('point_text') as $userId => $weekValues) {
                foreach ($weekValues as $week => $value) {
                    if (empty($value)) {
                        continue;
                    }

                    $dataColl->put('user_id', $userId);
                    $dataColl->put('periode_week', $week);
                    $dataColl->put('point_text', $value);
                    $dataColl->put('point', parsePointToInt($value));

                    $memberLimit = UserMemberLimit::updateOrCreate(
                        $dataColl->only('user_id', 'master_gaya_id', 'periode_week', 'periode_month', 'periode_year')->all(),
                        $dataColl->only('point', 'point_text')->all(),
                    );
                    if (! $memberLimit) {
                        DB::rollback();

                        return back()
                            ->withInput()
                            ->withErrors(["Batch {$this->moduleName} baru GAGAL disimpan!"]);
                    }
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

    public function ajaxLimitGayaFromMember(Request $request)
    {
        if (! $request->ajax()
            || ! $request->filled('user_id')
        ) {
            return response()->json(['error' => 'Data not found!'], 404);
        }

        $memberLimitTable = (new UserMemberLimit)->getTable();
        $masterGayaIds = UserMemberLimit::where('user_id', $request->user_id)
            ->when($request->filled('periode_start') && $request->filled('periode_end'), function ($query) use ($request, $memberLimitTable) {
                $periodeStart = explode('-', $request->periode_start);
                $periodeEnd = explode('-', $request->periode_end);
                $query->where(function ($query) use ($memberLimitTable, $periodeStart) {
                    $query->where($memberLimitTable.'.periode_year', '>=', $periodeStart[1])
                        ->where($memberLimitTable.'.periode_month', '>=', $periodeStart[0]);
                })->where(function ($query) use ($memberLimitTable, $periodeEnd) {
                    $query->where($memberLimitTable.'.periode_year', '<=', $periodeEnd[1])
                        ->where($memberLimitTable.'.periode_month', '<=', $periodeEnd[0]);
                });
            })
            ->pluck('master_gaya_id')
            ->unique()
            ->values()
            ->all();

        $data = MasterGaya::whereIn('id', $masterGayaIds)
            ->orderByRaw('CAST(`name` AS UNSIGNED) ASC')
            ->pluck('name', 'id');

        return response()->json($data, 200);
    }
}
