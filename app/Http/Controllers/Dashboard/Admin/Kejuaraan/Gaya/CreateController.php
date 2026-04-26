<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Gaya;

use App\Libraries\FormFields;
use App\Models\MasterChampionshipGaya;
use Illuminate\Http\Request;

class CreateController extends BaseController
{
    public function __invoke(Request $request, MasterChampionshipGaya $gaya)
    {
        $formFields = new FormFields($gaya);
        $formFields = $formFields->generateForm();
        // dd($formFields);

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'gaya' => $formFields,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }
}
