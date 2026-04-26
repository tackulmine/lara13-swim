<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Report;

use App\Models\ChampionshipEvent;
use App\Models\MasterChampionshipGaya;
use App\Models\UserChampionship;
use Illuminate\Http\Request;

class AjaxGayaController extends BaseController
{
    public function __invoke(Request $request)
    {
        if (
            ! $request->ajax()
            || ! $request->filled('user_id')
        ) {
            return response()->json(['error' => 'Data not found!'], 404);
        }

        $championshipEventTable = (new ChampionshipEvent)->getTable();
        $data = MasterChampionshipGaya::when(
            $request->filled('user_id'),
            function ($q) use ($request, $championshipEventTable) {
                $q->whereIn(
                    'id',
                    UserChampionship::where('user_id', $request->user_id)
                        ->when($request->filled('periode_start') && $request->filled('periode_end'), function ($query) use ($request, $championshipEventTable) {
                            $query->whereHas('championshipEvent', function ($query) use ($request, $championshipEventTable) {
                                $periodeStart = explode('-', $request->periode_start);
                                $periodeEnd = explode('-', $request->periode_end);

                                $query->where(function ($query) use ($periodeStart, $championshipEventTable) {
                                    $query->whereMonth($championshipEventTable.'.start_date', '>=', (int) $periodeStart[0])
                                        ->whereYear($championshipEventTable.'.start_date', '>=', (int) $periodeStart[1]);
                                })->where(function ($query) use ($periodeEnd, $championshipEventTable) {
                                    $query->whereMonth($championshipEventTable.'.start_date', '<=', (int) $periodeEnd[0])
                                        ->whereYear($championshipEventTable.'.start_date', '<=', (int) $periodeEnd[1]);
                                });
                            });
                        })
                        ->pluck('master_championship_gaya_id')
                        ->unique()
                        ->values()
                        ->all()
                );
            }
        )
            ->orderByRaw('CAST(`name` AS UNSIGNED) ASC')
            ->pluck('name', 'id');

        return response()->json($data, 200);
    }
}
