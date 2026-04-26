<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvitationRequest;
use App\Models\Invitation;

class InvitationsController extends Controller
{
    public function store(StoreInvitationRequest $request)
    {
        $invitation = new Invitation($request->all());
        $invitation->generateInvitationToken();
        $invitation->save();

        return redirect()->route('requestInvitation')
            ->with('success', __('Invitation to register successfully requested. Please wait for registration link.'));
    }
}
