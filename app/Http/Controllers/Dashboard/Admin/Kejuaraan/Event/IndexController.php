<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event;

use App\Models\ChampionshipEvent;
use App\Models\MasterChampionship;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __invoke(Request $request)
    {
        $championshipEventTable = (new ChampionshipEvent)->getTable();
        $masterChampionshipTable = (new MasterChampionship)->getTable();
        $events = ChampionshipEvent::with([
            'masterChampionship',
            'userChampionships' => function ($q) {
                $q->whereHas('user', function ($query) {
                    $query->select('id');
                });
            },
        ])
            ->withCount([
                'userChampionships' => function ($q) {
                    $q->whereHas('user', function ($query) {
                        $query->select('id');
                    });
                },
            ])
            ->whereHas('masterChampionship', function ($query) {
                $query->select('id');
            })
            ->when($request->filled('trashed'), function ($q) {
                $q->onlyTrashed();
            })
            ->orderBy('start_date', 'desc')
            ->get();

        $breadcrumbs = [
            '' => $this->moduleName,
        ];

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}".($request->filled('trashed') ? ' Non Aktif' : ' Aktif'),
            'events' => $events,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
