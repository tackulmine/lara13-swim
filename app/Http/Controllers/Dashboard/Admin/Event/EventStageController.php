<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Exports\ParticipantsPerStageExport;
use App\Http\Controllers\Dashboard\Admin\BaseController;
use App\Libraries\FormFields;
use App\Models\Event;
use App\Models\EventStage;
use App\Models\MasterMatchCategory;
use App\Models\MasterMatchType;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class EventStageController extends BaseController
{
    private $customMessages;

    private $customAttributes;

    public $typeOptions;

    public $categoryOptions;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Acara';
        $this->baseRouteName = 'dashboard.admin.event.stage.';
        $this->baseViewPath = 'dashboard.admin.event.stage.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'number' => 'Nomor Acara',
            'master_match_type_id' => __('Gaya'),
            'master_match_category_id' => __('Kategori'),
        ];
    }

    protected function generateOptions()
    {
        $this->typeOptions = MasterMatchType::pluck('name', 'id');
        $this->categoryOptions = MasterMatchCategory::pluck('name', 'id');

        $this->globalData = [
            'typeOptions' => $this->typeOptions,
            'categoryOptions' => $this->categoryOptions,
        ] + $this->globalData;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, Event $event)
    {
        $event->load([
            'eventStages',
            'eventStages.masterMatchType',
            'eventStages.masterMatchCategory',
            'eventSessions',
            'eventSessions.eventSessionParticipants',
        ]);
        $eventStages = $event->eventStages()
        // ->withCount(['eventSessions'])
            ->orderBy('order_number', 'asc')
            ->orderBy('number', 'asc')
            ->get();
        $eventStages->load([
            'masterMatchType',
            'masterMatchCategory',
            'eventSessions',
            'eventSessionParticipants',
        ]);

        $breadcrumbs = [
            route('dashboard.admin.event.index') => 'Kompetisi',
            '' => $this->moduleName,
        ];

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}
                - ".$event->name,
            'event' => $event,
            'eventStages' => $eventStages,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Event $event, EventStage $eventStage)
    {
        $formFields = new FormFields($eventStage);
        $formFields = $formFields->generateForm();

        $this->generateOptions();

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'event' => $event,
            'eventStage' => $formFields,
            'typeOptions' => $this->typeOptions->prepend('-- select --', ''),
            'categoryOptions' => $this->categoryOptions->prepend('-- select --', ''),
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
    public function store(Request $request, Event $event)
    {
        $rules = [
            'number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique(EventStage::table())->where(function ($query) use ($event) {
                    return $query->where('event_id', $event->id);
                }),
            ],
            'master_match_type_id' => 'required|exists:'.MasterMatchType::table().',id',
            'master_match_category_id' => 'required|exists:'.MasterMatchCategory::table().',id',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $validatedData = $validatedData + $request->all();

        if (! $event->eventStage()->create(array_filter($validatedData, 'trim'))) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$event->name' GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index', $event->id)
            ->withSuccess("{$this->moduleName} '$event->name' telah disimpan!");
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    // public function show(Request $request, Event $event, EventStage $eventStage)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(Request $request, Event $event, EventStage $eventStage)
    {
        $formFields = new FormFields($eventStage);
        $formFields = $formFields->generateForm();

        $this->generateOptions();

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} ".str_pad($eventStage->number, 3, 0, STR_PAD_LEFT),
            'event' => $event,
            'eventStage' => $formFields,
            'id' => $eventStage->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, Event $event, EventStage $eventStage)
    {
        $rules = [
            'number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique(EventStage::table())->where(function ($query) use ($event, $eventStage) {
                    return $query->where('event_id', $event->id)
                        ->where('id', '<>', $eventStage->id);
                }),
            ],
            'master_match_type_id' => 'required|exists:'.MasterMatchType::table().',id',
            'master_match_category_id' => 'required|exists:'.MasterMatchCategory::table().',id',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $validatedData = $validatedData + $request->all();

        if (! $eventStage->update($validatedData)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$eventStage->number' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '$eventStage->number' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', array_merge(['event' => $event->id], getQueryParams()))
            ->withSuccess("{$this->moduleName} '$eventStage->number' telah diupdate.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(Request $request, Event $event, EventStage $eventStage)
    {
        if (! $eventStage->delete()) {
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
            ->route($this->baseRouteName.'index', array_merge(['event' => $event->id], getQueryParams()))
            ->withSuccess("{$this->moduleName} telah dihapus.");
    }

    public function download(Request $request, Event $event, EventStage $eventStage)
    {
        $eventStage->load([
            // 'event',
            'masterMatchType',
            'masterMatchCategory',
            'eventSessionParticipants',
        ]);

        $eventParticipants = $eventStage->eventSessionParticipants()
            ->orderBy('disqualification', 'asc')
            ->orderBy('point', 'asc')
            ->get();

        $eventParticipants->load([
            'masterParticipant',
            'masterParticipant.masterSchool',
            'eventSession',
            'eventSession.eventStage',
            'eventSession.eventStage.masterMatchType',
            'eventSession.eventStage.masterMatchCategory',
        ]);

        $filename = $event->slug.'-stage-'.$eventStage->number_format.'-result';

        if ($request->filled('type') && $request->input('type') == 'pdf') {
            $headerHtml = view($this->baseViewPath.'exports.pdf-participants-header', compact('event', 'eventStage'))->render();
            $footerHtml = view($this->baseViewPath.'exports.pdf-participants-footer', compact('event', 'eventStage'))->render();

            $pdf = SnappyPdf::loadView($this->baseViewPath.'exports.pdf-participants', [
                'event' => $event,
                'eventStage' => $eventStage,
                'participants' => $eventParticipants,
            ]);
            // $pdf->setOption('enable-javascript', true);
            // $pdf->setOption('javascript-delay', 5000);
            $pdf->setOption('enable-smart-shrinking', true);
            $pdf->setOption('no-stop-slow-scripts', true);
            $pdf->setOption('margin-top', '45mm');
            $pdf->setOption('margin-bottom', '20mm');
            $pdf->setOption('header-html', $headerHtml);
            $pdf->setOption('footer-html', $footerHtml);

            // return $pdf->download($filename . '.pdf');
            return $pdf->inline($filename.'.pdf');
        }

        if ($request->filled('type') && $request->input('type') == 'view_only') {
            return view($this->baseViewPath.'exports.pdf-participants', [
                'event' => $event,
                'eventStage' => $eventStage,
                'participants' => $eventParticipants,
            ]);
        }

        $export = new ParticipantsPerStageExport($event, $eventStage, $eventParticipants);

        return Excel::download($export, $filename.'.xlsx');
    }
}
