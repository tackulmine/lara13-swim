<?php

namespace App\Http\Controllers\Dashboard\Admin\Member;

use App\Models\MasterUserType;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __invoke(Request $request)
    {
        // $members = User::has('userMember')
        $members = User::whereHas('userType', function ($q) {
            $q->where('master_user_type_id', MasterUserType::MEMBER_ID);
        })
            ->with('profile', 'userMember', 'userMember.type', 'userMember.class', 'educations', 'educations.school')
            ->where('id', '<>', 1)
            ->latest()
            ->when(! auth()->user()->isSuperuser() && auth()->user()->isMember(), function ($q) {
                $q->where('id', auth()->id());
            })
            ->when($request->filled('trashed'), function ($q) {
                $q->onlyTrashed();
            })
            ->get();

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName} ".($request->filled('trashed') ? 'Non Aktif' : 'Aktif'),
            'members' => $members,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
