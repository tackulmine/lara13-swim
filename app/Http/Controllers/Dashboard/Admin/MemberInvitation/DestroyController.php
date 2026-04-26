<?php

namespace App\Http\Controllers\Dashboard\Admin\MemberInvitation;

use App\Models\Invitation;
use Illuminate\Http\Request;

class DestroyController extends BaseController
{
    public function __invoke(Request $request, Invitation $invitation)
    {
        if (! $invitation->delete()) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL hapus permanen!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL hapus permanen!"]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah hapus permanen."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah hapus permanen.");
    }
}
