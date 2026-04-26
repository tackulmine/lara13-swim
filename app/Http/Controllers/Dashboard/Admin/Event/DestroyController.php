<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DestroyController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request, Event $event)
    {
        if (! auth()->user()->hasRole('coach')
            and $event->created_by != auth()->id()
        ) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        // check if event/stage/session has been completed
        // if ($event->completed || $event->eventSessions->where('completed', true)->count()) {
        // check if only event has been completed cz we have demo event
        if ($event->completed) {
            return redirect()
                ->route($this->baseRouteName.'index')
                ->withErrors(["{$this->moduleName} TIDAK diijinkan dihapus!"]);
        }

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
