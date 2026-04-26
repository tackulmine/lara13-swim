<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event;

use App\Libraries\FormFields;
use App\Models\ChampionshipEvent;
use Illuminate\Http\Request;

class EditController extends BaseController
{
    public function __invoke(Request $request, ChampionshipEvent $event)
    {
        // dd($event->toArray());
        $formFields = new FormFields($event);
        $formFields = $formFields->generateForm();
        $formFields->name = old('name', $event->masterChampionship->name);

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$event->name}",
            'event' => $formFields,
            'id' => $event->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        // dd($this->globalData);
        return view($this->baseViewPath.'edit', $this->globalData);
    }
}
