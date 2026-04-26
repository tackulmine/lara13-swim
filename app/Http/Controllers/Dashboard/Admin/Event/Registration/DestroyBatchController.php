<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Registration;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestroyBatchController extends BaseController
{
    public function __invoke(Request $request, Event $event)
    {
        $event->load([
            'eventRegistrations.types',
            'eventRegistrations.masterMatchCategory',
            'eventRegistrations.masterParticipant.masterSchool',
        ]);

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
                $ids = explode('_', $id, 2);
                $eventRegistration = $event->eventRegistrations()
                    ->where('id', $ids[0])
                    ->first();
                $eventRegistrationType = $eventRegistration->types();

                optional($eventRegistrationType)->detach($ids[1]);
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
            ->route($this->baseRouteName.'index', $event)
            ->withSuccess("{$this->moduleName} telah dihapus.");
    }
}
