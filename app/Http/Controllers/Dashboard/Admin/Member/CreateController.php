<?php

namespace App\Http\Controllers\Dashboard\Admin\Member;

use App\Libraries\FormFields;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserMember;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class CreateController extends BaseController
{
    public function __invoke(Request $request, User $member)
    {
        $this->generateOptions();

        $member->load('profile', 'userMember', 'userMember.type', 'educations', 'educations.school');

        $formFields = new FormFields($member);
        $formFields = $formFields->generateForm();

        foreach ((new UserProfile)->getFillable() as $key) {
            $formFields->$key = old($key);
        }
        // $formFields->birth_date = Carbon::createFromFormat('d/M/Y', old('birth_date', now()->subYears(3)->format('d/M/Y')));
        $formFields->photo_url = $member->photo_url;
        $formFields->preview_fancy_photo = $member->preview_fancy_photo;

        foreach ((new UserMember)->getFillable() as $key) {
            $formFields->$key = old($key);
        }

        foreach ((new UserEducation)->getFillable() as $key) {
            $formFields->$key = old($key);
        }
        $formFields->master_school = old('master_school');

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'member' => $formFields,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }
}
