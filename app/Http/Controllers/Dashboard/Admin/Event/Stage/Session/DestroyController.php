<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventStage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DestroyController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request, Event $event, EventStage $eventStage, EventSession $eventSession)
    {
        if (! auth()->user()->hasRole('coach')
            and $event->created_by != auth()->id()
        ) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        if (! $eventSession->delete()) {
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
            ->route($this->baseRouteName.'index', array_merge([
                'event' => $event->id,
                'eventStage' => $eventStage->id,
            ], getQueryParams()))
            ->withSuccess("{$this->moduleName} telah dihapus.");
    }
}
