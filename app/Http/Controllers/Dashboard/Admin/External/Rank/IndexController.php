<?php

namespace App\Http\Controllers\Dashboard\Admin\External\Rank;

use App\Models\ExternalAthleteBestTime;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __invoke(Request $request)
    {
        $bestTimes = ExternalAthleteBestTime::with([
            'externalSwimmingStyle',
            'externalSwimmingAthlete.externalSwimmingClub.masterCity.masterProvince',
            'externalSwimmingEvent',
        ])
            ->when($request->filled('trashed'), function ($q) {
                $q->onlyTrashed();
            })
            ->orderBy('year', 'desc')
            ->orderBy('point', 'asc')
            ->get();

        $breadcrumbs = [
            '' => $this->moduleName,
        ];

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}",
            'bestTimes' => $bestTimes,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
