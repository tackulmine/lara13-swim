<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage;

use App\Http\Controllers\Dashboard\Admin\BaseController;
use App\Libraries\FormFields;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventStage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class EventSessionController extends BaseController
{
    protected $stageOptions;

    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Seri';
        $this->baseRouteName = 'dashboard.admin.event.stage.session.';
        $this->baseViewPath = 'dashboard.admin.event.stage.session.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'session' => 'Nomor Seri',
            'event_stage_id' => 'Nomor Acara',
        ];
    }

    protected function generateOptions($eventId)
    {
        $this->stageOptions = EventStage::whereEventId($eventId)->get(['id', 'number']);

        $this->stageOptions = $this->stageOptions->mapWithKeys(function ($eventStage) {
            return [$eventStage->id => str_pad($eventStage->number, 3, 0, STR_PAD_LEFT)];
        });

        $this->globalData = [
            'stageOptions' => $this->stageOptions,
        ] + $this->globalData;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, Event $event, EventStage $eventStage)
    {
        $eventStage->load([
            'event',
            'masterMatchType',
            'masterMatchCategory',
            'eventSessionParticipants',
        ]);
        $eventSessions = $eventStage->eventSessions()
        // ->withCount(['eventSessions'])
            ->orderBy('session', 'asc')
            ->get();
        $eventSessions->load([
            'eventStage',
            'eventSessionParticipants' => function ($q) {
                $q->has('masterParticipant');
            },
        ]);

        $breadcrumbs = [
            route('dashboard.admin.event.index') => 'Kompetisi',
            route('dashboard.admin.event.stage.index', $event) => 'Acara',
            '' => $this->moduleName,
        ];

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName} -
                Acara ".$eventStage->numberFormat.'
                '.$eventStage->masterMatchType->name.'
                '.$eventStage->masterMatchCategory->name.'
                - '.$event->name,
            'event' => $event,
            'eventStage' => $eventStage,
            'eventSessions' => $eventSessions,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Event $event, EventStage $eventStage, EventSession $eventSession)
    {
        $formFields = new FormFields($eventSession);
        $formFields = $formFields->generateForm();

        $eventStage->load('event');
        $this->generateOptions($eventStage->event->id);

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'event' => $event,
            'eventStage' => $eventStage,
            'eventSession' => $formFields,
            'stageOptions' => $this->stageOptions->prepend('-- select --', ''),
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, Event $event, EventStage $eventStage)
    {
        $eventStageNumber = str_pad($eventStage->number, 3, 0, STR_PAD_LEFT);

        $rules = [
            'session' => [
                'required',
                'integer',
                'min:1',
                Rule::unique(EventSession::table())->where(function ($query) use ($eventStage) {
                    return $query->where('event_stage_id', $eventStage->id);
                }),
            ],
            // 'event_stage_id' => 'required|exists:' . EventStage::table() . ',id',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $validatedData = $validatedData + $request->all();

        if (! $eventStage->eventSessions()->create(array_filter($validatedData, 'trim'))) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$eventStageNumber' GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index', $eventStage->id)
            ->withSuccess("{$this->moduleName} '$eventStageNumber' telah disimpan!");
    }

    /**
     * Display the specified resource.
     *
     * @param  EventStage  $eventSession
     * @return Response
     */
    // public function show(Request $request, EventStage $eventStage, EventSession $eventSession)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  EventStage  $eventSession
     * @return Response
     */
    public function edit(Request $request, Event $event, EventStage $eventStage, EventSession $eventSession)
    {
        $formFields = new FormFields($eventSession);
        $formFields = $formFields->generateForm();

        $eventStage->load('event');
        $this->generateOptions($eventStage->event->id);

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} ".$eventSession->session,
            'event' => $event,
            'eventStage' => $eventStage,
            'eventSession' => $formFields,
            'id' => $eventSession->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EventStage  $eventSession
     * @return Response
     */
    public function update(Request $request, Event $event, EventStage $eventStage, EventSession $eventSession)
    {
        $rules = [
            'session' => [
                'required',
                'integer',
                'min:1',
                Rule::unique(EventSession::table())->where(function ($query) use ($eventStage, $eventSession) {
                    return $query->where('event_stage_id', $eventStage->id)
                        ->where('id', '<>', $eventSession->id);
                }),
            ],
            // 'event_stage_id' => 'required|exists:' . EventStage::table() . ',id',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $validatedData = $validatedData + $request->all();

        if (! $eventSession->update($validatedData)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$eventSession->session' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '$eventSession->session' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', array_merge(['eventStage' => $eventStage->id], getQueryParams()))
            ->withSuccess("{$this->moduleName} '$eventSession->session' telah diupdate.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  EventStage  $eventSession
     * @return Response
     */
    public function destroy(Request $request, Event $event, EventStage $eventStage, EventSession $eventSession)
    {
        if (! $eventSession->delete()) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL dihapus!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL dihapus!"]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah dihapus."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', array_merge(['eventStage' => $eventStage->id], getQueryParams()))
            ->withSuccess("{$this->moduleName} telah dihapus.");
    }
}
