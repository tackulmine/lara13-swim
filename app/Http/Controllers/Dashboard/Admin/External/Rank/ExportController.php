<?php

namespace App\Http\Controllers\Dashboard\Admin\External\Rank;

use App\Exports\ExternalAthleteBestTimeExport;
use App\Models\ExternalSwimmingStyle;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __invoke(Request $request)
    {
        $styles = ExternalSwimmingStyle::with([
            'bestTimes' => function ($q) {
                $q->with([
                    'externalSwimmingAthlete.externalSwimmingClub.masterCity.masterProvince',
                    'externalSwimmingEvent',
                ])
                    ->orderBy('point');
            },
        ])
            ->orderBy('id')
            ->get();

        if ($request->filled('view_only')) {
            return view('dashboard.admin.external.rank.exports.best-time', [
                'styles' => $styles,
            ]);
        }

        return Excel::download(new ExternalAthleteBestTimeExport($styles), 'athlete-besttime.xlsx');
    }
}
