<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IndexController extends BaseController
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
    public function __invoke(Request $request)
    {
        $events = Event::with([
            'eventStages',
            'eventSessions',
            'eventParticipants' => function ($q) {
                $q->hasIn('masterParticipant');
            },
            // 'eventSessions.eventSessionParticipants' => function ($q) {
            //     $q->hasIn('masterParticipant');
            // },
            // 'eventRegistrations',
        ])
            // ->withCount(['eventStages', 'eventSessions'])
            ->withCount(['eventRegistrations'])
            ->when($request->filled('trashed'), function ($q) {
                $q->onlyTrashed();
            })
            // check if role is external
            ->when(! auth()->user()->isSuperuser() && auth()->user()->hasRole('external'), function ($q) {
                $q->where('is_external', 1)
                    ->where('created_by', auth()->id());
            })
            ->orderBy('start_date', 'desc')
            ->get();

        $breadcrumbs = [
            '' => $this->moduleName,
        ];

        $this->globalData = [
            // 'pageTitle' => "Daftar {$this->moduleName}",
            'pageTitle' => "Daftar {$this->moduleName}".($request->filled('trashed') ? ' Non Aktif' : ' Aktif'),
            'events' => $events,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
