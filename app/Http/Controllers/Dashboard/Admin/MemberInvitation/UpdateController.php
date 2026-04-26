<?php

namespace App\Http\Controllers\Dashboard\Admin\MemberInvitation;

use App\Models\Invitation;
use Illuminate\Http\Request;

class UpdateController extends BaseController
{
    public function __invoke(Request $request, Invitation $invitation)
    {
        $invitationTable = (new Invitation)->getTable();
        $rules = [
            'email' => 'filled|email|unique:'.$invitationTable.',email,'.$invitation->id.',id',
        ];

        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        // IF FAILED
        if (! $invitation->update($request->only('email'))) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '{$invitation->name}' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '{$invitation->name}' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} '{$invitation->name}' telah diupdate.");
    }
}
