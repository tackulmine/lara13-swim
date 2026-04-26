<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Models\MasterGaya;
use App\Models\User;
use App\Models\UserMemberLimit;
use Illuminate\Http\Request;

class MemberReportController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Rapor Atlit';
        $this->baseRouteName = 'dashboard.admin.member-report.';
        $this->baseViewPath = 'dashboard.admin.member-report.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
    }

    protected function generateOptions(Request $request)
    {
        $members = User::member()
            ->when(! auth()->user()->isSuperuser() && auth()->user()->isMember(), function ($q) {
                $q->where('id', auth()->id());
            })
            ->orderBy('name')
            ->get(['id', 'username', 'name']);
        $memberOptions = [];
        foreach ($members as $member) {
            $memberOptions[$member->id] = "$member->name ($member->username)";
        }

        $memberLimitTable = (new UserMemberLimit)->getTable();
        $this->globalData = [
            'memberOptions' => collect($memberOptions)->prepend('-- Pilih --', ''),
            'masterGayaOptions' => MasterGaya::when($request->filled('user_id'), function ($q) use ($request, $memberLimitTable) {
                $q->whereIn(
                    'id',
                    UserMemberLimit::where('user_id', ! auth()->user()->isSuperuser() && auth()->user()->isMember() ? auth()->id() : $request->user_id)
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
                        ->all()
                );
            })
                ->orderByRaw('CAST(`name` AS UNSIGNED) ASC')->pluck('name', 'id')->prepend('-- Pilih --', ''),
        ] + $this->globalData;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->isSuperuser() && auth()->user()->isMember() && $request->user_id > 0 && $request->user_id != auth()->id()) {
            return redirect()->route('dashboard.admin.member-report.index', $request->merge(['user_id' => auth()->id()])->all());
        }

        $this->generateOptions($request);

        $data = [
            'pageTitle' => "{$this->moduleName}",
        ];

        if (
            $request->has('user_id')
            || $request->has('master_gaya_id')
            || $request->has('periode_start')
            || $request->has('periode_end')
        ) {
            $memberLimits = UserMemberLimit::with('user', 'gaya')
                ->when($request->filled('user_id'), function ($query) use ($request) {
                    $query->whereHas('user', function ($query) use ($request) {
                        $query->where('user_id', $request->user_id);
                    });
                })
                ->when($request->filled('master_gaya_id'), function ($query) use ($request) {
                    $query->whereHas('gaya', function ($query) use ($request) {
                        $query->where('master_gaya_id', $request->master_gaya_id);
                    });
                })
                ->when($request->filled('periode_start') && $request->filled('periode_end'), function ($query) use ($request) {
                    $periodeStart = explode('-', $request->periode_start);
                    $periodeEnd = explode('-', $request->periode_end);

                    $query->where(function ($query) use ($periodeStart) {
                        $query->where('periode_year', '>=', $periodeStart[1])
                            ->where('periode_month', '>=', $periodeStart[0]);
                    })->where(function ($query) use ($periodeEnd) {
                        $query->where('periode_year', '<=', $periodeEnd[1])
                            ->where('periode_month', '<=', $periodeEnd[0]);
                    });
                })
                ->orderBy('periode_year')
                ->orderBy('periode_month')
                ->orderBy('periode_week')
                ->get();

            $data['memberLimits'] = $memberLimits;
            $data['user'] = $user = User::member()
                ->with(
                    'userMember',
                    'profile',
                    'educations',
                    'educations.school',
                    'gayaLimits',
                )
                ->find($request->user_id);
            $data['gaya'] = $gaya = MasterGaya::find($request->master_gaya_id);

            $periodeStart = explode('-', request()->periode_start);
            $periodeEnd = explode('-', request()->periode_end);
            $periode = parseBetweenDateCustom(date($periodeStart[1].'-'.$periodeStart[0].'-01'), date($periodeEnd[1].'-'.$periodeEnd[0].'-01'));
            $periode = str_replace('&ndash;', '-', $periode);

            $data['pageTitle'] = "{$this->moduleName} - {$user->name} - {$gaya->name} - {$periode}";
        }

        $this->globalData = $data + $this->globalData;

        if ($request->filled('print')) {
            return view($this->baseViewPath.'result', $this->globalData);
        }

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
