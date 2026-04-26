<?php

namespace App\Http\Controllers\Dashboard\Admin\MemberInvitation;

use App\Models\Invitation;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __invoke(Request $request)
    {
        $invitations = Invitation::when($request->filled('completed'), function ($q) {
            $q->whereNotNull('registered_at');
        }, function ($q) {
            $q->whereNull('registered_at');
        })->latest()->get();

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}".($request->filled('completed') ? ' (Selesai)' : ''),
            'invitations' => $invitations,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
