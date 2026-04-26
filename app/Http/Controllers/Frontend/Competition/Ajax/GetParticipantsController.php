<?php

namespace App\Http\Controllers\Frontend\Competition\Ajax;

use App\Http\Controllers\Frontend\Competition\BaseController;
use App\Models\Event;
use App\Models\MasterSchool;
use Illuminate\Http\Request;

class GetParticipantsController extends BaseController
{
    public function __invoke(Request $request, string $eventSlug)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();

        // participants
        $participantOptions = [];
        if ($request->filled('school')) {
            $masterSchool = MasterSchool::where('name', $request->school)->first();
            // logger()->info('$masterSchool->name');
            if (! empty($masterSchool)) {
                // logger()->debug($masterSchool->name);

                $participants = $this->getMasterParticipants($event, $masterSchool);
                foreach ($participants as $participant) {
                    $participantOptions[] = [
                        'id' => addslashes($participant->name),
                        'text' => addslashes($participant->name).' ('.parseGenderAbbr($participant->gender).', '.($participant->birth_year ?? '-').')',
                    ];
                }
            }
        }

        return response()->json(['results' => $participantOptions]);
    }
}
