<?php

namespace App\Http\Controllers\Frontend\Competition;

use App\Models\Event;
use App\Models\EventStage;
use Illuminate\Http\Request;

class CompleteController extends BaseController
{
    public function __invoke(Request $request, string $eventSlug)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();
        $eventStage = EventStage::whereId($request->event_stage)->firstOrFail();

        if ($eventStage->eventSessions()->whereCompleted(0)->doesntExist()) {
            $eventStage->completed = 1;
            $eventStage->updated_by = auth()->id();
            if ($eventStage->save()) {
                return back()
                    ->withSuccess('Acara sebelumnya telah Selesai!');
            }
        }

        return back()
            ->withInput()
            ->withErrors(['GAGAL memuat acara selanjutnya!']);
    }
}
