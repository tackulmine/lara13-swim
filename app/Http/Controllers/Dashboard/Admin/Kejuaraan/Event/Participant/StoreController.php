<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event\Participant;

use App\Models\ChampionshipEvent;
use App\Models\MasterChampionshipGaya;
use App\Models\UserChampionship;
use App\Models\UserMember;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
    public function __invoke(
        Request $request,
        ChampionshipEvent $event,
        UserChampionship $userChampionship
    ) {
        $rules = [
            'user_id' => [
                'required',
                'exists:'.UserMember::table().',user_id',
            ],
            'master_championship_gaya_id' => [
                'required',
                'exists:'.MasterChampionshipGaya::table().',id',
            ],
            'point_text' => [
                'required',
            ],
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $validatedData['point'] = parsePointToInt($request->point_text);
        // $validatedData['rank'] = $request->rank;
        $validatedData = $validatedData + $request->all();

        // dd($request->toArray(), $validatedData, array_filter($validatedData, 'trim'));
        $participant = $event->userChampionships()->create(array_filter($validatedData, 'trim'));
        if (! $participant) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} Baru GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index', [$event])
            ->withSuccess("{$this->moduleName} '{$participant->user->name}' telah disimpan!");
    }
}
