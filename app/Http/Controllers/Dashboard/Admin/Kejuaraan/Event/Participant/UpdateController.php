<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event\Participant;

use App\Models\ChampionshipEvent;
use App\Models\MasterChampionshipGaya;
use App\Models\UserChampionship;
use App\Models\UserMember;
use Illuminate\Http\Request;

class UpdateController extends BaseController
{
    public function __invoke(
        Request $request,
        ChampionshipEvent $event,
        UserChampionship $userChampionship
    ) {
        $userChampionship->load(['user', 'masterChampionshipGaya']);
        $participantDetail = "{$userChampionship->user->username} ({$userChampionship->user->name}";

        $rules = [
            'user_id' => [
                'filled',
                'exists:'.UserMember::table().',user_id',
            ],
            'master_championship_gaya_id' => [
                'filled',
                'exists:'.MasterChampionshipGaya::table().',id',
            ],
            'point_text' => [
                'filled',
            ],
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $validatedData['point'] = parsePointToInt($request->point_text);
        // $validatedData['rank'] = $request->rank;
        $validatedData = $validatedData + $request->all();

        // dd($request->toArray(), $validatedData, array_filter($validatedData, 'trim'));
        if (! $userChampionship->update($validatedData)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '{$participantDetail})' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '{$participantDetail})' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', array_merge([
                'event' => $event->id,
            ], getQueryParams()))
            ->withSuccess("{$this->moduleName} '{$participantDetail})' telah diupdate.");
    }
}
