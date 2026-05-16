<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Models\Event;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DownloadEventBookController extends BaseController
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
        $eventStages = $event->eventStages()
            ->orderBy('order_number', 'asc')
            ->orderBy('number', 'asc')
            ->get();

        $eventStages->load([
            'masterMatchType',
            'masterMatchCategory',
            'eventSessions' => function ($q) {
                $q->withCount(['eventSessionParticipants' => function ($q) {
                    $q->has('masterParticipant');
                }])
                    ->orderBy('session', 'asc');
            },
            'eventSessions.eventSessionParticipants' => function ($q) {
                $q->has('masterParticipant')
                    ->orderBy('track', 'asc');
            },
            'eventSessionParticipants' => function ($q) {
                $q->has('masterParticipant')
                    ->orderBy('track', 'asc');
            },
            // 'eventSessions.eventSessionParticipants.masterParticipant',
            'eventSessions.eventSessionParticipants.masterParticipant.styles',
            'eventSessions.eventSessionParticipants.masterParticipant.masterSchool',
        ]);
        // dd($eventStages[0]->toArray());

        // dd($event->eventParticipants()->has('masterParticipant')->pluck('track')->toArray());
        // dd($event->eventParticipants()->has('masterParticipant')->pluck('track')->unique()->toArray());
        // $minTrack = 1; // force to 1
        // if ($event->start_track_number != 1) {
        //     $minTrack = 0;
        // }

        // $tracks = $event->eventParticipants()->has('masterParticipant')->pluck('track')->unique();

        $cacheKey = '|event|'.$event->id.'|tracks|unique|';
        $tracks = cache()->remember($cacheKey, config('cache.ttl'), function () use ($event) {
            return $event->eventParticipants()->has('masterParticipant')->pluck('track')->unique();
        });
        $minTrack = $tracks->min();
        $maxTrack = $tracks->max();
        // dd($minTrack, $maxTrack);

        // ONLY BY REQUEST CUSTOM HALF AND FULL TRACK
        // $maxTracks = [];
        // foreach ($eventStages as $eventStage) {
        //     // $cacheKey = '|event|'.$event->id.'|event-stage|'.$eventStage->id.'|participants|max-track|';
        //     // $eventStageMaxTrack = cache()->remember($cacheKey, config('cache.ttl'), function () use ($eventStage) {
        //     //     return $eventStage
        //     //         ->eventSessionParticipants()
        //     //         ->pluck('track')
        //     //         ->unique()
        //     //         ->max();
        //     // });
        //     // $maxTrack = $eventStageMaxTrack;
        //     // if ($maxTrack < 5) {
        //     //     $maxTrack = 5;
        //     // }
        //     // if ($maxTrack > 5 and $maxTrack < 10) {
        //     //     $maxTrack = 10;
        //     // }
        //     $maxTracks[$eventStage->id] = $maxTrack;
        // }

        $filename = $event->slug.'-event';

        if ($request->filled('view_only')) {
            return view($this->baseViewPath.'exports.event.pdf-event-book-dom', [
                'event' => $event,
                'eventStages' => $eventStages,
                'minTrack' => $minTrack,
                'maxTrack' => $maxTrack,
                // 'maxTracks' => $maxTracks,
            ]);
        }

        // $headerHtml = view($this->baseViewPath . 'exports.event.pdf-event-header', compact('event'))->render();
        // $footerHtml = view($this->baseViewPath . 'exports.event.pdf-event-footer', compact('event'))->render();

        $pdf = Pdf::loadView($this->baseViewPath.'exports.event.pdf-event-book-dom', [
            'event' => $event,
            'eventStages' => $eventStages,
            'minTrack' => $minTrack,
            'maxTrack' => $maxTrack,
            // 'maxTracks' => $maxTracks,
        ])->setPaper('folio', 'portrait');

        $pdf->setOption([
            'dpi' => 150,
            'defaultFont' => 'Bahnschrift',
            'enable_php' => true,
        ]);

        // $pdf->setPaper('legal-folio', 'portrait');
        // Ukuran Folio dalam point: 612 x 936
        // $customPaper = [0, 0, 612, 936];
        // $pdf->setPaper($customPaper, 'portrait');

        // if (!app()->environment('production')) {
        return $pdf->stream($filename.'.pdf');
        // }

        // return $pdf->download($filename . '.pdf');
    }
}
