<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Gaya;

use App\Models\MasterChampionshipGaya;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __invoke(Request $request)
    {
        $gayas = MasterChampionshipGaya::orderByRaw('CAST(`name` AS UNSIGNED) ASC')
            ->withCount('userChampionships')
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
}
