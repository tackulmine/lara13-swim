<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session;

use App\Http\Controllers\Dashboard\Admin\BaseController;
use App\Libraries\FormFields;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use App\Models\MasterParticipant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class EventParticipantController extends BaseController
{
    protected $participantOptions;

    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Peserta';
        $this->baseRouteName = 'dashboard.admin.event.stage.session.participant.';
        $this->baseViewPath = 'dashboard.admin.event.stage.session.participant.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'master_participant_id' => 'Peserta',
            'track' => 'Lintasan',
            'point' => 'Poin Waktu',
            'point_text' => 'Poin Waktu',
            'disqualification' => 'Dis',
            'notes' => 'Keterangan',
        ];
    }

    protected function generateOptions()
    {
        $this->participantOptions = MasterParticipant::with('masterSchool')->orderBy('name')->get(['id', 'master_school_id', 'name']);

        $this->participantOptions = $this->participantOptions->mapWithKeys(function ($participant) {
            $masterSchoolName = optional($participant->masterSchool)->name ?? '-';

            return [$participant->id => $participant->name." ( {$masterSchoolName} )"];
        });

        $this->globalData = [
            'participantOptions' => $this->participantOptions,
        ] + $this->globalData;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(
        Request $request,
        Event $event,
        EventStage $eventStage,
        EventSession $eventSession
    ) {
        $eventSession->load([
            'eventStage',
            'eventSessionParticipants',
        ]);
        // participants
        $eventSessionParticipants = $eventSession->eventSessionParticipants()
        // ->withCount(['eventSessionParticipants'])
            ->orderBy('track', 'asc')
            ->get();
        $eventSessionParticipants->load([
            'masterParticipant',
            'masterParticipant.masterSchool',
        ]);
        // // stage
        // $eventStage->load([
        //     'masterMatchType',
        //     'masterMatchCategory',
        //     // 'eventSessionParticipants',
        // ]);
        // // event
        // $event = $eventStage->event->load([
        //     'eventStages',
        //     'eventStages.masterMatchType',
        //     'eventStages.masterMatchCategory',
        //     'eventSessions',
        //     'eventSessions.eventSessionParticipants',
        // ]);

        $breadcrumbs = [
            route('dashboard.admin.event.index') => 'Kompetisi',
            route('dashboard.admin.event.stage.index', $event) => 'Acara',
            route('dashboard.admin.event.stage.session.index', [$event, $eventStage]) => 'Seri',
            '' => $this->moduleName,
        ];

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName} Seri ".$eventSession->session.' -
                Acara '.$eventStage->numberFormat.'
                '.$eventStage->masterMatchType->name.'
                '.$eventStage->masterMatchCategory->name.'
                - '.$event->name,
            'event' => $event,
            'eventStage' => $eventStage,
            'eventSession' => $eventSession,
            'eventSessionParticipants' => $eventSessionParticipants,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(
        Request $request,
        Event $event,
        EventStage $eventStage,
        EventSession $eventSession,
        EventSessionParticipant $eventSessionParticipant
    ) {
        $formFields = new FormFields($eventSessionParticipant);
        $formFields = $formFields->generateForm();

        $eventSession->load('eventStage');
        $this->generateOptions();
        // // stage
        // $eventStage = $eventSession->eventStage->load('eventSessionParticipants');
        // // event
        // $event = $eventStage->event->load([
        //     'eventStages',
        //     'eventStages.masterMatchType',
        //     'eventStages.masterMatchCategory',
        //     'eventSessions',
        //     'eventSessions.eventSessionParticipants',
        // ]);

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'event' => $event,
            'eventStage' => $eventStage,
            'eventSession' => $eventSession,
            'eventSessionParticipant' => $formFields,
            'participantOptions' => $this->participantOptions->prepend('-- select --', ''),
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
    public function store(
        Request $request,
        Event $event,
        EventStage $eventStage,
        EventSession $eventSession
    ) {
        $rules = [
            'track' => [
                'required',
                'integer',
                'min:1',
                Rule::unique(EventSessionParticipant::table())->where(function ($query) use ($eventSession) {
                    return $query->where('event_session_id', $eventSession->id);
                }),
            ],
            'disqualification' => 'filled|boolean',
            'master_participant_id' => 'required|exists:'.MasterParticipant::table().',id',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $validatedData['point_text'] = $request->point_text;
        $validatedData['point'] = parsePointToInt($request->point_text);
        if (empty($request->disqualification)) {
            $validatedData['disqualification'] = false;
        }
        $validatedData = $validatedData + $request->all();

        // dd($request->toArray(), $validatedData, array_filter($validatedData, 'trim'));
        $eventSessionParticipant = $eventSession->eventSessionParticipants()->create(array_filter($validatedData, 'trim'));
        if (! $eventSessionParticipant) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} Baru GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index', [
                'event' => $event->id,
                'eventStage' => $eventStage->id,
                'eventSession' => $eventSession->id,
            ])
            ->withSuccess("{$this->moduleName} '$eventSessionParticipant->name' telah disimpan!");
    }

    /**
     * Display the specified resource.
     *
     * @param  EventStage  $eventSession
     * @return Response
     */
    // public function show(Request $request, EventSession $eventSession, EventSessionParticipant $eventSessionParticipant)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  EventStage  $eventSession
     * @return Response
     */
    public function edit(
        Request $request,
        Event $event,
        EventStage $eventStage,
        EventSession $eventSession,
        EventSessionParticipant $eventSessionParticipant
    ) {
        $formFields = new FormFields($eventSessionParticipant);
        $formFields = $formFields->generateForm();

        $eventSessionParticipant->load(['masterParticipant', 'masterParticipant.masterSchool']);
        $eventSession->load('eventStage');
        $this->generateOptions();
        // // stage
        // $eventStage = $eventSession->eventStage->load('eventSessionParticipants');
        // // event
        // $event = $eventStage->event->load([
        //     'eventStages',
        //     'eventStages.masterMatchType',
        //     'eventStages.masterMatchCategory',
        //     'eventSessions',
        //     'eventSessions.eventSessionParticipants',
        // ]);

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} ".$eventSessionParticipant->masterParticipant->name.'
                ('.(optional($eventSessionParticipant->masterParticipant->masterSchool)->name ?? '-').')',
            'event' => $event,
            'eventStage' => $eventStage,
            'eventSession' => $eventSession,
            'eventSessionParticipant' => $formFields,
            'id' => $eventSessionParticipant->id,
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
    public function update(
        Request $request,
        Event $event,
        EventStage $eventStage,
        EventSession $eventSession,
        EventSessionParticipant $eventSessionParticipant
    ) {
        $eventSessionParticipant->load(['masterParticipant', 'masterParticipant.masterSchool']);
        $participantDetail = "{$eventSessionParticipant->masterParticipant->name} "
        .'('.optional($eventSessionParticipant->masterParticipant->masterSchool)->name.')';

        $rules = [
            'track' => [
                'required',
                'integer',
                'min:1',
                Rule::unique(EventSessionParticipant::table())->where(function ($query) use ($eventSession, $eventSessionParticipant) {
                    return $query->where('event_session_id', $eventSession->id)
                        ->where('id', '<>', $eventSessionParticipant->id);
                }),
            ],
            'disqualification' => 'filled|boolean',
            'master_participant_id' => 'required|exists:'.MasterParticipant::table().',id',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $validatedData['point_text'] = $request->point_text;
        $validatedData['point'] = parsePointToInt($request->point_text);
        if (empty($request->disqualification)) {
            $validatedData['disqualification'] = false;
        }
        if (empty($request->point_text)) {
            $validatedData['point'] = null;
        }
        $validatedData = $validatedData + $request->all();

        // dd($request->toArray(), $validatedData, array_filter($validatedData, 'trim'));
        if (! $eventSessionParticipant->update($validatedData)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '{$participantDetail})' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '{$participantDetail})' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', array_merge([
                'event' => $event->id,
                'eventStage' => $eventStage->id,
                'eventSession' => $eventSession->id,
            ], getQueryParams()))
            ->withSuccess("{$this->moduleName} '{$participantDetail})' telah diupdate.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  EventStage  $eventSession
     * @return Response
     */
    public function destroy(
        Request $request,
        Event $event,
        EventStage $eventStage,
        EventSession $eventSession,
        EventSessionParticipant $eventSessionParticipant
    ) {
        if (! $eventSessionParticipant->delete()) {
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
            ->route($this->baseRouteName.'index', array_merge([
                'event' => $event->id,
                'eventStage' => $eventStage->id,
                'eventSession' => $eventSession->id,
            ], getQueryParams()))
            ->withSuccess("{$this->moduleName} telah dihapus.");
    }
}
