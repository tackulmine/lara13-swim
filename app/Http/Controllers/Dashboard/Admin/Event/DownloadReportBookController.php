<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Models\Event;
use App\Models\EventSession;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DownloadReportBookController extends BaseController
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
        $eventSessionTable = (new EventSession)->getTable();
        $eventStages = $event->eventStages()
            ->where('completed', true)
            ->orderBy('order_number')
            ->orderBy('number')
            ->get();

        $eventStages->load([
            'masterMatchType',
            'masterMatchCategory',
            // 'eventSessions',
            'eventSessionParticipants' => function ($q) use ($eventSessionTable) {
                $q->where($eventSessionTable.'.completed', true)
                    ->has('masterParticipant')
                    ->orderBy('disqualification')
                    ->orderBy('dis_level')
                    ->orderBy('point');
            },
            'eventSessionParticipants.masterParticipant.styles',
            'eventSessionParticipants.masterParticipant.masterSchool',
        ]);

        $filename = $event->slug.'-result';

        if ($request->filled('view_only')) {
            return view($this->baseViewPath.'exports.report.pdf-report-book', [
                'event' => $event,
                'eventStages' => $eventStages,
            ]);
        }

        $headerHtml = view($this->baseViewPath.'exports.report.pdf-report-header', compact('event'))->render();
        $footerHtml = view($this->baseViewPath.'exports.report.pdf-report-footer', compact('event'))->render();

        $pdf = SnappyPdf::loadView($this->baseViewPath.'exports.report.pdf-report-book', [
            'event' => $event,
            'eventStages' => $eventStages,
        ]);
        $pdf->setPaper('folio');
        // $pdf->setOption('enable-javascript', true);
        // $pdf->setOption('javascript-delay', 5000);
        $pdf->setOption('enable-smart-shrinking', true);
        $pdf->setOption('no-stop-slow-scripts', true);
        $pdf->setOption('margin-top', '30mm');
        $pdf->setOption('margin-bottom', '15mm');
        $pdf->setOption('margin-left', '8mm');
        $pdf->setOption('margin-right', '8mm');
        $pdf->setOption('header-html', $headerHtml);
        $pdf->setOption('footer-html', $footerHtml);

        // if (!app()->environment('production')) {
        return $pdf->inline($filename.'.pdf');
        // }

        // return $pdf->download($filename . '.pdf');
    }
}
