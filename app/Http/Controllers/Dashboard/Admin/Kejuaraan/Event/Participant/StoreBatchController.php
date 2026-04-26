<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event\Participant;

use App\Models\ChampionshipEvent;
use App\Models\MasterChampionshipGaya;
use App\Models\UserChampionship;
use App\Models\UserMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreBatchController extends BaseController
{
    public function __invoke(
        Request $request,
        ChampionshipEvent $event,
        UserChampionship $userChampionship
    ) {
        $rules = [
            'user_id.*' => [
                'required',
                'exists:'.UserMember::table().',user_id',
            ],
            'master_championship_gaya_id.*' => [
                'required',
                'exists:'.MasterChampionshipGaya::table().',id',
            ],
            'point_text.*' => [
                'required',
            ],
            // 'rank.*' => [
            //     'required',
            // ],
        ];
        $this->customAttributes = [
            'user_id.*' => 'Nama',
            'master_championship_gaya_id.*' => __('Gaya'),
            'point_text.*' => 'Poin',
            'rank.*' => 'Ranking',
        ];

        $usersData = $request->input('user_id');
        $this->customAttributes = collect($usersData)
            ->mapWithKeys(function ($userData, $index) {
                return ["user_id.{$index}" => 'Nama pada baris ke-'.($index + 1)];
            })
            ->merge($this->customAttributes)
            ->toArray();
        $gayasData = $request->input('master_championship_gaya_id');
        $this->customAttributes = collect($gayasData)
            ->mapWithKeys(function ($gayaData, $index) {
                return ["master_championship_gaya_id.{$index}" => 'Gaya pada baris ke-'.($index + 1)];
            })
            ->merge($this->customAttributes)
            ->toArray();
        $pointsData = $request->input('point_text');
        $this->customAttributes = collect($pointsData)
            ->mapWithKeys(function ($pointData, $index) {
                return ["point_text.{$index}" => 'Poin pada baris ke-'.($index + 1)];
            })
            ->merge($this->customAttributes)
            ->toArray();
        // $ranksData = $request->input('rank');
        // $this->customAttributes = collect( $ranksData )
        //     ->mapWithKeys( function ( $rankData, $index )  {
        //         return [ "rank.{$index}" => "Ranking pada baris ke-".($index+1) ];
        //     } )
        //     ->merge( $this->customAttributes )
        //     ->toArray();

        $validator = Validator::make($request->all(), $rules, $this->customMessages, $this->customAttributes);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }

        foreach ($request->user_id as $index => $userId) {
            $event->userChampionships()->updateOrCreate([
                'user_id' => $userId,
                'master_championship_gaya_id' => $request->input('master_championship_gaya_id.'.$index),
            ], [
                'rank' => $request->input('rank.'.$index),
                'point_text' => $request->input('point_text.'.$index),
                'point' => parsePointToInt($request->input('point_text.'.$index)),
            ]);
        }

        return redirect()
            ->route($this->baseRouteName.'index', [$event])
            ->withSuccess("Batch {$this->moduleName} telah disimpan!");
    }
}
