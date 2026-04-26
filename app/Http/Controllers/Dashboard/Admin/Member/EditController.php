<?php

namespace App\Http\Controllers\Dashboard\Admin\Member;

use App\Libraries\FormFields;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserMember;
use App\Models\UserProfile;
use Carbon\Carbon;

class EditController extends BaseController
{
    public function __invoke(User $member)
    {
        if (! auth()->user()->isSuperuser() && auth()->user()->isMember() && $member->id != auth()->id()) {
            return redirect()->route('dashboard.admin.member.edit', auth()->id());
        }

        $this->generateOptions();

        $member->load('profile', 'userMember', 'userMember.type', 'educations', 'educations.school');

        $formFields = new FormFields($member);
        $formFields = $formFields->generateForm();

        $userProfile = optional($member->profile);
        foreach ((new UserProfile)->getFillable() as $key) {
            $formFields->$key = old($key, $userProfile->$key);
        }
        // $formFields->birth_date = Carbon::createFromFormat('d/M/Y', old('birth_date', optional($userProfile->birth_date)->format('d/M/Y') ?? now()->subYears(3)->format('d/M/Y')));
        $formFields->birth_date = old('birth_date', (
            $userProfile->birth_date
            ? Carbon::createFromFormat('d/M/Y', optional($userProfile->birth_date)->format('d/M/Y'))
            : null));
        $formFields->photo_url = $member->photo_url;
        $formFields->preview_fancy_photo = $member->preview_fancy_photo;

        $formFields->preview_fancy_birth_certificate = $member->preview_fancy_birth_certificate;
        $formFields->birth_certificate_url = $member->birth_certificate_url;
        $formFields->preview_birth_certificate = $member->preview_birth_certificate;

        $formFields->preview_fancy_family_card = $member->preview_fancy_family_card;
        $formFields->family_card_url = $member->family_card_url;
        $formFields->preview_family_card = $member->preview_family_card;

        $formFields->preview_fancy_kta_card = $member->preview_fancy_kta_card;
        $formFields->kta_card_url = $member->kta_card_url;
        $formFields->preview_kta_card = $member->preview_kta_card;

        $formFields->preview_fancy_signature = $member->preview_fancy_signature;

        $userMember = optional($member->userMember);
        foreach ((new UserMember)->getFillable() as $key) {
            $formFields->$key = old($key, $userMember->$key);
        }

        $userEducation = optional(optional($member->educations)->first());
        foreach ((new UserEducation)->getFillable() as $key) {
            $formFields->$key = old($key, $userEducation->$key);
        }
        $formFields->master_school = old('master_school', optional($userEducation->school)->name);

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$member->name}",
            'member' => $formFields,
            'id' => $member->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }
}
