<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event\Participant;

use App\Models\ChampionshipEvent;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __invoke(Request $request, ChampionshipEvent $event)
    {
        $event->load([
            'masterChampionship',
            'userChampionships',
        ]);
        // participants
        $participants = $event->userChampionships()
            ->whereHas('user', function ($query) {
                $query->select('id');
            })
            // ->orderBy('rank', 'asc')
            ->get();
        $participants->load([
            'user',
            'masterChampionshipGaya',
        ]);

        $breadcrumbs = [
            route('dashboard.admin.kejuaraan.event.index') => 'Kejuaraan',
            '' => $this->moduleName,
        ];

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName} - Kejuaraan ".$event->masterChampionship->name,
            'breadcrumbs' => $breadcrumbs,
            'event' => $event,
            'participants' => $participants,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
