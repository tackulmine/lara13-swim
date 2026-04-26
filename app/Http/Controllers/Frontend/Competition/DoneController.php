<?php

namespace App\Http\Controllers\Frontend\Competition;

use App\Models\Event;
use Illuminate\Http\Request;

class DoneController extends BaseController
{
    public function __invoke(Request $request, string $eventSlug)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();

        if ($event->eventStages()->whereCompleted(0)->doesntExist()) {
            $event->completed = 1;
            $event->updated_by = auth()->id();
            if ($event->save()) {
                return back()
                    ->withSuccess('Kompetisi telah Selesai!');
            }
        }

        return back()
            ->withInput()
            ->withErrors(['GAGAL menyelesaikan Kompetisi!']);
    }
}
