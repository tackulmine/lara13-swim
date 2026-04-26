<?php

namespace App\Http\Controllers\Dashboard\Admin\Member;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DestroyBatchController extends BaseController
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

        $batchDestroy = false;
        DB::beginTransaction();
        try {
            foreach ($request->ids as $id) {
                $user = User::find($id);
                $user->username = 'archived_'.$user->username;
                $user->email = 'archived_'.$user->email;
                $user->save();
                $user->delete();
            }

            DB::commit();
            $batchDestroy = true;
        } catch (\Throwable $th) {
            // throw $th;
            Log::error('Error [Batch Delete]: '.$th->getMessage());
            DB::rollback();
        }

        if (! $batchDestroy) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL dinon-aktifkan!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL dinon-aktifkan!"]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah dinon-aktifkan."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah dinon-aktifkan.");
    }
}
