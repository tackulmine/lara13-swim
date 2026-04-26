<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event;

use App\Models\ChampionshipEvent;
use Illuminate\Http\Request;

class DestroyController extends BaseController
{
    public function __invoke(Request $request, ChampionshipEvent $event)
    {
        if (! $event->delete()) {
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
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah dihapus.");
    }
}
