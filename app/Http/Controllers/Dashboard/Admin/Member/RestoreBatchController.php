<?php

namespace App\Http\Controllers\Dashboard\Admin\Member;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RestoreBatchController extends BaseController
{
    public function __invoke(Request $request)
    {
        if (! auth()->user()->isSuperuser() && auth()->user()->isMember()) {
            abort(403);
        }

        if (! $request->filled('ids')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        $batchRestore = false;
        DB::beginTransaction();
        try {
            foreach ($request->ids as $id) {
                $user = User::onlyTrashed()->find($id);

                $username = str_replace('archived_', '', $user->username);
                if (User::where('username', $username)->exists()) {
                    $username = $user->id.'_'.$username;
                }
                $user->username = $username;

                $email = str_replace('archived_', '', $user->email);
                if (User::where('email', $email)->exists()) {
                    $email = $user->id.'_'.$email;
                }
                $user->email = $email;

                $user->save();
                $user->restore();
            }

            DB::commit();
            $batchRestore = true;
        } catch (\Throwable $th) {
            // throw $th;
            Log::error('Error [Batch Restore]: '.$th->getMessage());
            DB::rollback();
        }

        if (! $batchRestore) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL diaktifkan!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL diaktifkan!"]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah diaktifkan."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah diaktifkan.");
    }
}
