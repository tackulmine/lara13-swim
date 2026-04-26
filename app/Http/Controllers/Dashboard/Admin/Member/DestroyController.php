<?php

namespace App\Http\Controllers\Dashboard\Admin\Member;

use App\Models\User;
use Illuminate\Http\Request;

class DestroyController extends BaseController
{
    public function __invoke(Request $request, User $member)
    {
        if (! auth()->user()->isSuperuser() && auth()->user()->isMember()) {
            abort(403);
        }

        if (! $member->delete()) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL dihapus!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL dihapus!"]);
        }

        $member->username = 'archived_'.$member->username;
        $member->email = 'archived_'.$member->email;
        $member->save();

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah dihapus."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah dihapus.");
    }
}
