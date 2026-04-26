<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event;

use App\Models\ChampionshipEvent;
use App\Models\MasterChampionship;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
    public function __invoke(Request $request)
    {
        $rules = [
            // 'name' => 'required|unique:'.MasterChampionship::table().'|max:255',
            'name' => 'required|max:255',
            // 'address' => 'required',
            'location' => 'required',
            'date' => 'required',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $parseDate = array_filter(array_map('trim', explode('-', $request->date)), 'strlen');
        // dd($parseDate);
        $validatedData['start_date'] = Carbon::createFromFormat('d/m/Y', $parseDate[0]);
        $validatedData['end_date'] = Carbon::createFromFormat('d/m/Y', $parseDate[1]);
        $masterChampionship = MasterChampionship::updateOrCreate(
            ['name' => $request->name],
            ['name' => $request->name],
        );
        $validatedData['master_championship_id'] = $masterChampionship->id;
        $validatedData = $validatedData + $request->all();

        $event = ChampionshipEvent::create(array_filter($validatedData, 'trim'));
        if (! $event) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '{$event->masterChampionship->name}' Baru GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("{$this->moduleName} '{$event->masterChampionship->name}' telah disimpan!");
    }
}
