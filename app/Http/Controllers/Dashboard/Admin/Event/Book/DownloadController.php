<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Book;

use App\Exports\EventBookExport;
use App\Libraries\FinaSeedingService;
use App\Models\Event;
use App\Models\EventRegistrationNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DownloadController extends BaseController
{
    use EventBookTrait;

    public function __invoke(Request $request, Event $event)
    {
        $this->generateEventNumbers($event, $request->boolean('reset_all'));
        $eventBooks = $this->generateSwimmingEventBooks($event);
        // dd($eventBooks[0]->group[0]->masterMatchType->name);

        if ($request->input('view_only')) {
            $html = '';
            foreach ($eventBooks as $eventBook) {
                $html .= view('dashboard.admin.event.book.exports.swimming-sessions', [
                    'event' => $event,
                    'eventBook' => $eventBook,
                    'eventNumbers' => $eventBooks,
                ])->render();
                $html .= '<hr />';
            }

            return $html;
        }

        return (new EventBookExport($event, $eventBooks))->download("event-book-{$event->slug}.xlsx");
    }

    private function generateSwimmingEventBooks(Event $event)
    {
        $eventData = EventRegistrationNumber::query()
            ->with(['masterMatchType', 'masterMatchCategory'])
            ->where('event_id', $event->id)
            ->orderBy('order_number')
            ->get();

        $allRegistrations = $this->getAllRegistrationsForEvent($event->id, $eventData->pluck('master_match_type_id', 'master_match_category_id'));

        // Group by match type name without putra/putri
        $groupedData = $eventData->groupBy(function ($item) {
            return $this->cleanMatchTypeName($item->masterMatchType->name);
        });
        // dd($groupedData);
        // dump($groupedData);

        return $groupedData->map(function ($group, $cleanName) use ($allRegistrations, $event) {
            $allSessions = collect();
            $totalParticipants = 0;

            foreach ($group as $eventNumber) {
                // dump($eventNumber);
                $key = $eventNumber->master_match_category_id.'_'.$eventNumber->master_match_type_id;
                // dump($key);
                $registrations = $allRegistrations->get($key, collect());
                // dump($eventNumber->masterMatchCategory->name . ' - ' . $registrations->count());
                $sessions = $this->groupRegistrationsIntoSessions($registrations, $event->total_track ?? 10, $event->start_track_number);

                $allSessions = $allSessions->merge($sessions);
                $totalParticipants += $registrations->count();
            }

            return (object) [
                'group' => $group,
                'sheetName' => $cleanName,
                'sessions' => $allSessions->values(),
                'total_participants' => $totalParticipants,
            ];
        })->values();
    }

    private function cleanMatchTypeName($name)
    {
        return trim(preg_replace('/\b(putra|putri|pa|pi)\b/i', '', $name));
    }

    private function getAllRegistrationsForEvent($eventId, $categoryTypeMap)
    {
        $registrations = DB::table('event_registrations')
            ->join('event_registration_style', 'event_registrations.id', '=', 'event_registration_style.event_registration_id')
            ->join('master_participants', 'event_registrations.master_participant_id', '=', 'master_participants.id')
            ->leftJoin('master_schools', 'master_participants.master_school_id', '=', 'master_schools.id')
            ->where('event_registrations.event_id', $eventId)
            ->whereNull('event_registrations.deleted_at')
            ->select([
                'event_registrations.master_match_category_id',
                'event_registration_style.master_match_type_id',
                'master_participants.id as participant_id',
                'master_participants.name as participant_name',
                'master_participants.gender',
                'master_participants.birth_year',
                'master_schools.name as school_name',
                'event_registration_style.point',
                'event_registration_style.point_text',
                'event_registration_style.is_no_point',
            ])
            ->get();

        return $registrations->groupBy(function ($item) {
            return $item->master_match_category_id.'_'.$item->master_match_type_id;
        })->map(function ($items) {
            return $items->map(function ($item) {
                return (object) [
                    'masterParticipant' => (object) [
                        'id' => $item->participant_id,
                        'name' => $item->participant_name,
                        'gender_text' => $item->gender === 'male' ? 'Laki-laki' : 'Perempuan',
                        'birth_year' => $item->birth_year,
                        'masterSchool' => $item->school_name ? (object) ['name' => $item->school_name] : null,
                    ],
                    'types' => collect([(object) [
                        'pivot' => (object) [
                            'point' => $item->point,
                            'point_text' => $item->point_text,
                            'is_no_point' => $item->is_no_point,
                        ],
                    ]]),
                ];
            });
        });
    }

    private function groupRegistrationsIntoSessions($registrations, $lanesPerSession = 10, $startLane = 1)
    {
        if ($registrations->isEmpty()) {
            return collect();
        }

        $registrationOrders = $registrations->map(function ($registration, $laneIndex) use ($startLane) {
            $achievement = optional($registration->types->first());

            return (object) [
                'lane_number' => $startLane ? $laneIndex + 1 : $laneIndex,
                'participant' => $registration->masterParticipant,
                'birth_year' => $registration->masterParticipant->birth_year,
                'school' => $registration->masterParticipant->masterSchool,
                'achievement' => intval((string) parsePointToInt($achievement->pivot->point_text)) > 0 ? $achievement->pivot->point_text : null,
            ];
        });

        $newRegistrationOrders = (new FinaSeedingService)->generateHeats($registrationOrders->shuffle(), $lanesPerSession, $startLane);

        return $newRegistrationOrders
            ->sortBy(fn ($item) => [$item->seri, $item->lintasan])
            ->groupBy('seri')
            ->map(fn ($registration, $seri) => (object) [
                'session_number' => $seri,
                'lanes' => $registration->values(),
            ]);
    }
}
