<?php

namespace App\Http\Controllers\Dashboard\Admin\Member;

use App\Models\User;
use Illuminate\Http\Request;

class PrintController extends BaseController
{
    public function __invoke(Request $request, User $member)
    {
        if (! auth()->user()->isSuperuser() && auth()->user()->isMember() && $member->id != auth()->id()) {
            return redirect()->route('dashboard.admin.member.print', auth()->id());
        }

        $member->load('profile', 'userMember', 'userMember.type', 'educations', 'educations.school');

        $this->globalData = [
            'pageTitle' => "Detail {$this->moduleName} - {$member->name}".($member->userMember ? ' - '.optional($member->userMember)->nis : ''),
            'member' => $member,
            'id' => $member->id,
        ] + $this->globalData;

        $this->globalData['coach'] = User::staff()
            ->whereHas('userStaff')
            ->orderBy('id')
            ->firstOrFail();

        return view($this->baseViewPath.'print', $this->globalData);
    }
}
