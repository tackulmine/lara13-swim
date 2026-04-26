<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Registration;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestroyBatchAtletController extends BaseController
{
    public function __invoke(Request $request, Event $event)
    {
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
                $eventRegistration = $event->eventRegistrations()
                    ->where('id', $id)
                    ->first();

                $eventRegistration->delete();
            }

            DB::commit();
            $batchDestroy = true;
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
        }

        if (! $batchDestroy) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL dihapus!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL dihapus!"]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah dihapus."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'atlet', $event)
            ->withSuccess("{$this->moduleName} telah dihapus.");
    }
}
