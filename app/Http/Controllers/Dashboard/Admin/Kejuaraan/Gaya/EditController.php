<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Gaya;

use App\Libraries\FormFields;
use App\Models\MasterChampionshipGaya;

class EditController extends BaseController
{
    public function __invoke(MasterChampionshipGaya $gaya)
    {
        $formFields = new FormFields($gaya);
        $formFields = $formFields->generateForm();
        // dd($formFields);

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$gaya->name}",
            'gaya' => $formFields,
            'id' => $gaya->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }
}
