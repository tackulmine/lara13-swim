<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event;

use App\Models\ChampionshipEvent;
use App\Models\MasterChampionship;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UpdateController extends BaseController
{
    public function __invoke(Request $request, ChampionshipEvent $event)
    {
        $event->load('masterChampionship');
        $rules = [
            // 'name' => 'filled|unique:'.MasterChampionship::table().',name,'.$event->id.',id|max:255',
            'name' => 'filled|unique:'.MasterChampionship::table().',name,'.$event->masterChampionship->id.',id|max:255',
            // 'address' => 'filled',
            'location' => 'filled',
            'date' => 'filled',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $parseDate = array_filter(array_map('trim', explode('-', $request->date)), 'strlen');
        // dd($parseDate);
        $validatedData['start_date'] = Carbon::createFromFormat('d/m/Y', $parseDate[0]);
        $validatedData['end_date'] = Carbon::createFromFormat('d/m/Y', $parseDate[1]);
        $validatedData = $validatedData + $request->all();

        if ($event->masterChampionship->name != $request->name) {
            $masterChampionship = MasterChampionship::find($event->master_championship_id)
                ->update([
                    'name' => $request->name,
                ]);
        }

        if (! $event->update($validatedData)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '{$event->masterChampionship->name}' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '{$event->masterChampionship->name}' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} '{$event->masterChampionship->name}' telah diupdate.");
    }
}
