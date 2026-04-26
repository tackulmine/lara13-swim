<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestoreBatchController extends BaseController
{
    public function __invoke(Request $request)
    {
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
                optional(Event::onlyTrashed()->find($id))->restore();
            }

            DB::commit();
            $batchRestore = true;
        } catch (\Throwable $th) {
            // throw $th;
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
