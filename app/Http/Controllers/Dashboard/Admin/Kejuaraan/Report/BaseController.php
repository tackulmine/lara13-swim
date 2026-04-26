<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Report;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;
use App\Models\ChampionshipEvent;
use App\Models\MasterChampionshipGaya;
use App\Models\User;
use App\Models\UserChampionship;
use Illuminate\Http\Request;

class BaseController extends ParentController
{
    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Rapor Kejuaraan';
        $this->baseRouteName = 'dashboard.admin.kejuaraan.report.';
        $this->baseViewPath = 'dashboard.admin.kejuaraan.report.';

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
                $q
                    ->where('id', auth()->id());
            }, function ($q) {
                $q->whereHas('userChampionships');
            })
            ->orderBy('name')
            ->get(['id', 'username', 'name']);
        $memberOptions = [];
        foreach ($members as $member) {
            $memberOptions[$member->id] = "$member->name ($member->username)";
        }

        $championshipEventTable = (new ChampionshipEvent)->getTable();
        $this->globalData = [
            'memberOptions' => collect($memberOptions)->prepend('-- Pilih --', ''),
            'masterGayaOptions' => MasterChampionshipGaya::when(
                $request->filled('user_id'),
                function ($q) use ($request, $championshipEventTable) {
                    $q->whereIn(
                        'id',
                        UserChampionship::where('user_id', ! auth()->user()->isSuperuser() && auth()->user()->isMember() ? auth()->id() : $request->user_id)
                            ->when($request->filled('periode_start') && $request->filled('periode_end'), function ($query) use ($request, $championshipEventTable) {
                                $query->whereHas(
                                    'championshipEvent',
                                    function ($query) use ($request, $championshipEventTable) {
                                        $periodeStart = explode('-', $request->periode_start);
                                        $periodeEnd = explode('-', $request->periode_end);

                                        $query->where(function ($query) use ($periodeStart, $championshipEventTable) {
                                            $query->whereMonth($championshipEventTable.'.start_date', '>=', (int) $periodeStart[0])
                                                ->whereYear($championshipEventTable.'.start_date', '>=', (int) $periodeStart[1]);
                                        })->where(function ($query) use ($periodeEnd, $championshipEventTable) {
                                            $query->whereMonth($championshipEventTable.'.start_date', '<=', (int) $periodeEnd[0])
                                                ->whereYear($championshipEventTable.'.start_date', '<=', (int) $periodeEnd[1]);
                                        });
                                    }
                                );
                            })
                            ->pluck('master_championship_gaya_id')
                            ->unique()
                            ->values()
                            ->all()
                    );
                }
            )
                ->orderByRaw('CAST(`name` AS UNSIGNED) ASC')
                ->pluck('name', 'id')
                ->prepend('-- Semua --', ''),
        ] + $this->globalData;
    }
}
