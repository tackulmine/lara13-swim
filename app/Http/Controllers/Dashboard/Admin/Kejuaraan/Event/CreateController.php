<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event;

use App\Libraries\FormFields;
use App\Models\ChampionshipEvent;
use Illuminate\Http\Request;

class CreateController extends BaseController
{
    public function __invoke(Request $request, ChampionshipEvent $event)
    {
        $formFields = new FormFields($event);
        $formFields = $formFields->generateForm();
        $formFields->name = old('name', '');

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'event' => $formFields,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }
}
