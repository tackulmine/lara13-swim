<?php

namespace App\Http\Controllers\Frontend\Competition\Ajax;

use App\Http\Controllers\Frontend\Competition\BaseController;
use App\Models\Event;
use App\Models\MasterSchool;
use Illuminate\Http\Request;

class GetParticipantController extends BaseController
{
    public function __invoke(Request $request, string $eventSlug)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();

        // participants
        $participant = collect();
        if ($request->filled('school') && $request->filled('name')) {
            $masterSchool = MasterSchool::where('name', $request->input('school'))->first();
            // logger()->info('$masterSchool->name');
            if (! empty($masterSchool)) {
                // logger()->debug($masterSchool->name);

                $participant = $this->getMasterParticipant($event, $masterSchool, $request);
            }
        }

        return response()->json($participant);
    }
}
