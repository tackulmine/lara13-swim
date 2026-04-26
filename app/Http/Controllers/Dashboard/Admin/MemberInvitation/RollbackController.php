<?php

namespace App\Http\Controllers\Dashboard\Admin\MemberInvitation;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RollbackController extends BaseController
{
    public function __invoke(Request $request, Invitation $invitation)
    {
        $exec = false;

        DB::beginTransaction();
        try {
            // code exec
            if ($user = User::where('email', $invitation->email)->first()) {
                $user->forceDelete();
            }

            $invitation->registered_at = null;
            $invitation->save();

            DB::commit();
            $exec = true;
        } catch (\Throwable $th) {
            // throw $th;

            DB::rollBack();
        }

        if (! $exec) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL diset untuk registrasi ulang!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL diset untuk registrasi ulang!"]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} berhasil diset untuk registrasi ulang."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} berhasil diset untuk registrasi ulang.");
    }
}
