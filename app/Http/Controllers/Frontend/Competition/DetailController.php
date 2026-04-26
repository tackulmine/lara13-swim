<?php

namespace App\Http\Controllers\Frontend\Competition;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Illuminate\Http\Request;

class DetailController extends BaseController
{
    public function __invoke(Request $request, string $eventSlug)
    {
        $event = Event::with([
            'eventStages',
            'eventStages.masterMatchCategory',
            'eventStages.masterMatchType',
        ])
            ->whereSlug($eventSlug)
            ->firstOrFail();

        $data = [];
        $data['event'] = $event;
        // dump($event->toArray()); die;

        $data['eventStage'] = $eventStage = $event->eventStages()
            ->where('completed', false)
            ->orderBy('completed')
            ->orderBy('order_number')
            ->orderBy('number')
            ->first();

        // check if event stage has been all completed n get the latest one of the event stage
        if (auth()->check() && ! $eventStage) {
            $data['eventStage'] = $eventStage = $event->eventStages()
                ->where('completed', true)
                ->orderByDesc('number')
                ->orderByDesc('order_number')
                ->first();
        }

        // $data['eventSession'] = $eventSession = EventSession::whereHas('eventStage', function ($q) use ($eventStage) {
        //     $q->where('event_stage_id', $eventStage->id);
        // })

        // dd($event->eventParticipants()->has('masterParticipant')->pluck('track')->unique()->toArray());
        // $data['minTrack'] = $data['maxTrack'] = 1; // force to 1
        // if ($event->start_track_number != 1) {
        //     $data['minTrack'] = 0;
        // }

        // ONLY BY REQUEST CUSTOM HALF AND FULL TRACK
        // if (! empty($eventStage->eventSessionParticipants)) {
        //     $cacheKey = '|event|'.$event->id.'|event-stage|'.$eventStage->id.'|participants|max-track|';
        //     $eventStageMaxTrack = cache()->remember($cacheKey, config('cache.ttl'), function () use ($eventStage) {
        //         return $eventStage
        //             ->eventSessionParticipants()
        //             ->pluck('track')
        //             ->unique()
        //             ->max();
        //     });
        //     $maxTrack = $eventStageMaxTrack;
        //     // if ($maxTrack < 5) {
        //     //     $maxTrack = 5;
        //     // }
        //     // if ($maxTrack > 5 and $maxTrack < 10) {
        //     //     $maxTrack = 10;
        //     // }
        //     $data['maxTrack'] = $maxTrack;
        // } elseif (! empty($eventStage->eventParticipants)) {
        // $tracks = $event->eventParticipants()->has('masterParticipant')->pluck('track')->unique();

        $cacheKey = '|event|'.$event->id.'|tracks|unique|';
        $tracks = cache()->remember($cacheKey, config('cache.ttl'), function () use ($event) {
            return $event->eventParticipants()->has('masterParticipant')->pluck('track')->unique();
        });
        $data['minTrack'] = $tracks->min();
        $data['maxTrack'] = $tracks->max();
        // }
        // dd($data['minTrack'], $data['maxTrack']);

        if (! empty($eventStage)) {
            $data['eventSession'] = $eventSession = $eventStage->eventSessions()
                ->orderBy('completed')
                ->orderBy('session')
                ->first();
            $data['currentEventSession'] = $eventSession;
            // dump($eventSession->toArray()); die;

            $data['eventSessions'] = EventSession::with([
                'eventSessionParticipants' => function ($q) {
                    $q->has('masterParticipant')
                        ->orderBy('track');
                },
                'eventSessionParticipants.masterParticipant',
                'eventSessionParticipants.masterParticipant.masterSchool',
            ])
                ->whereHas('eventStage', function ($q) use ($eventStage) {
                    $q->where('event_stage_id', $eventStage->id);
                })
                ->orderBy('session')
                ->get();
            // dump($data['eventSessions']->toArray()); die;

            $data['eventSessionParticipants'] = EventSessionParticipant::with([
                'eventSession',
                'masterParticipant',
                'masterParticipant.masterSchool',
            ])
                ->has('masterParticipant')
                ->whereHas('eventSession', function ($q) use ($eventSession) {
                    $q->where('event_session_id', $eventSession->id);
                    // ->whereCompleted(false);
                })
                // ->whereNull('point')
                ->orderBy('track')
                ->get();
            // dd($data['eventSessionParticipants']->toArray()); die;

            // $data['eventStagePointParticipants'] = EventSessionParticipant::with(['eventSession', 'masterParticipant', 'masterParticipant.masterSchool'])
            //     ->whereHas('eventSession.eventStage', function ($q) use ($eventStage) {
            //         $q->where('event_stage_id', $eventStage->id)
            //             ->whereCompleted(0);
            //     })
            //     ->whereNotNull('point')
            //     ->orderBy('track')
            //     ->get();
            // dump($data['eventStagePointParticipants']->toArray()); die;

            $data['eventStageRangkingParticipants'] = EventSessionParticipant::with([
                'eventSession',
                'masterParticipant',
                'masterParticipant.masterSchool',
            ])
                ->has('masterParticipant')
                ->whereHas('eventSession.eventStage', function ($q) use ($eventStage) {
                    $q->where('event_stage_id', $eventStage->id);
                    // ->whereCompleted(false);
                })
                ->whereNotNull('point')
                ->orderBy('disqualification')
                ->orderBy('dis_level')
                ->orderBy('point')
                ->orderBy('track')
                ->get();
            // dump($data['eventStageRangkingParticipants']->toArray()); die;
        }

        $data['pageTitle'] = $event->name;

        // excel values
        if ($request->filled('excel_values')) {
            $excelValues = collect($this->parseString($request->input('excel_values')));
            $data['excelValues'] = $excelValues->sortBy('lintasan')->keyBy('lintasan')->all();
            // dd($data['excelValues']);
            $data['excel_values'] = $request->input('excel_values');
        }

        return view('front.competition.detail', $data);
    }
}
