<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage;

use App\Exports\ParticipantsPerStageExport;
use App\Exports\ResultCertificatePerStageExport;
use App\Models\Event;
use App\Models\EventStage;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class DownloadController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request, Event $event, EventStage $eventStage)
    {
        $eventStage->load([
            // 'event',
            'masterMatchType',
            'masterMatchCategory',
            'eventSessionParticipants',
        ]);

        $eventParticipants = $eventStage->eventSessionParticipants()
            ->orderBy('disqualification')
            ->orderBy('dis_level')
            ->orderBy('point')
            ->get();

        $eventParticipants->load([
            // 'masterParticipant',
            'masterParticipant.styles',
            'masterParticipant.masterSchool',
            'eventSession',
            // 'eventSession.eventStage',
            'eventSession.eventStage.masterMatchType',
            'eventSession.eventStage.masterMatchCategory',
            'participantDetails',
        ]);

        // Certificate
        $filename = $event->slug.'-stage-'.$eventStage->number_format.'-result-certificate';

        if (
            $request->filled('type')
            && $request->input('type') == 'certificate'
        ) {
            if (
                $request->filled('ext')
                && $request->input('ext') == 'xls'
            ) {
                $export = new ResultCertificatePerStageExport($event, $eventStage, $eventParticipants);

                return Excel::download($export, $filename.'.xlsx');
            }

            return view($this->baseViewPath.'exports.result-certificate', [
                'event' => $event,
                'eventStage' => $eventStage,
                'participants' => $eventParticipants,
            ]);
        }

        // Result
        if (
            $request->filled('type')
            && $request->input('type') == 'result'
        ) {
            $filename = $event->slug.'-stage-'.$eventStage->number_format.'-result';

            if (
                $request->filled('ext')
                && $request->input('ext') == 'pdf'
            ) {
                $headerHtml = view($this->baseViewPath.'exports.pdf-participants-header', compact('event', 'eventStage'))->render();
                $footerHtml = view($this->baseViewPath.'exports.pdf-participants-footer', compact('event', 'eventStage'))->render();

                $pdf = SnappyPdf::loadView($this->baseViewPath.'exports.pdf-participants', [
                    'event' => $event,
                    'eventStage' => $eventStage,
                    'participants' => $eventParticipants,
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
                $pdf->setOption('header-font-name', 'Bahnschrift');
                $pdf->setOption('footer-html', $footerHtml);
                $pdf->setOption('footer-font-name', 'Bahnschrift');

                // return $pdf->download($filename . '.pdf');
                return $pdf->inline($filename.'.pdf');
            }

            if (
                $request->filled('ext')
                && $request->input('ext') == 'xls'
            ) {
                $export = new ParticipantsPerStageExport($event, $eventStage, $eventParticipants);

                return Excel::download($export, $filename.'.xlsx');
            }

            return view($this->baseViewPath.'exports.pdf-participants', [
                'event' => $event,
                'eventStage' => $eventStage,
                'participants' => $eventParticipants,
            ]);
        }
    }
}
