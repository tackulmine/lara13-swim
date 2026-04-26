<?php

namespace App\Http\Controllers\Dashboard\Admin\MemberInvitation;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;

class BaseController extends ParentController
{
    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = __('Requesting Invitation');
        $this->baseRouteName = 'dashboard.admin.member-invitation.';
        $this->baseViewPath = 'dashboard.admin.member-invitation.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [];
    }
}
