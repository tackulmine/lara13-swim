<?php

namespace App\Http\Controllers\Dashboard\Admin\MemberInvitation;

use App\Libraries\FormFields;
use App\Models\Invitation;

class EditController extends BaseController
{
    public function __invoke(Invitation $invitation)
    {
        $formFields = new FormFields($invitation);
        $formFields = $formFields->generateForm();
        // dd($formFields);

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$invitation->name}",
            'invitation' => $formFields,
            'id' => $invitation->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }
}
