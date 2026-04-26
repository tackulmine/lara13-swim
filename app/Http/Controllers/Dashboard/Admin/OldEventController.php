<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Imports\EventParticipantMultipleSheetImport;
use App\Imports\EventParticipantSingleSheetImport;
use App\Libraries\FormFields;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use App\Models\MasterMatchCategory;
use App\Models\MasterMatchType;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class OldEventController extends BaseController
{
    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Kompetisi';
        $this->baseRouteName = 'dashboard.admin.event.';
        $this->baseViewPath = 'dashboard.admin.event.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'name' => 'Nama Kompetisi',
            'address' => 'Alamat Kompetisi',
            'location' => 'Lokasi Kompetisi',
            'date' => 'Tanggal Kompetisi',
            'photo' => 'Logo Event',
            'file' => 'File Excel',
        ];
    }

    public function index()
    {
        $events = Event::with(['eventStages', 'eventSessions', 'eventSessions.eventSessionParticipants'])
        // ->withCount(['eventStages', 'eventSessions'])
            ->orderBy('start_date', 'desc')
            ->get();

        $breadcrumbs = [
            '' => $this->moduleName,
        ];

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}",
            'events' => $events,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    public function create(Event $event)
    {
        $formFields = new FormFields($event);
        $formFields = $formFields->generateForm();
        $formFields->photo_url = $event->photo_url;
        $formFields->photo_right_url = $event->photo_right_url;
        $formFields->preview_photo = $event->preview_photo;
        $formFields->preview_photo_right = $event->preview_photo_right;

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'event' => $formFields,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:'.Event::table().'|max:255',
            // 'address' => 'required',
            // 'location' => 'required',
            'date' => 'required',
        ];
        if ($request->hasFile('photo')) {
            $rules = [
                'photo' => [
                    'image',
                    'mimes:jpeg,jpg,png,gif',
                    'max:2048', // 2MB
                ],
            ] + $rules;
        }
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $parseDate = array_filter(array_map('trim', explode('-', $request->date)), 'strlen');
        // dd($parseDate);
        $validatedData['start_date'] = Carbon::createFromFormat('d/m/Y', $parseDate[0]);
        $validatedData['end_date'] = Carbon::createFromFormat('d/m/Y', $parseDate[1]);
        $validatedData = $validatedData + $request->all();

        $event = Event::create(array_filter($validatedData, 'trim'));
        if (! $event) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} Baru GAGAL disimpan!"]);
        }

        // update photo
        if ($request->hasFile('photo')) {
            // inject data attributes to update photo
            $event->photo = uploadEventPhoto($request->photo, $event->id);
            $event->save();
        }

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("{$this->moduleName} '$event->name' telah disimpan!");
    }

    public function edit(Event $event)
    {
        // dd($event->toArray());
        $formFields = new FormFields($event);
        $formFields = $formFields->generateForm();
        $formFields->photo_url = $event->photo_url;
        $formFields->photo_right_url = $event->photo_right_url;
        $formFields->preview_photo = $event->preview_photo;
        $formFields->preview_photo_right = $event->preview_photo_right;

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$event->name}",
            'event' => $formFields,
            'id' => $event->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        // dd($this->globalData);
        return view($this->baseViewPath.'edit', $this->globalData);
    }

    public function update(Request $request, Event $event)
    {
        $rules = [
            'name' => 'filled|unique:'.Event::table().',name,'.$event->id.',id|max:255',
            // 'address' => 'filled',
            // 'location' => 'filled',
            'date' => 'filled',
        ];
        if ($request->hasFile('photo')) {
            $rules = [
                'photo' => [
                    'image',
                    'mimes:jpeg,jpg,png,gif',
                    'max:2048', // 2MB
                ],
            ] + $rules;
        }
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $parseDate = array_filter(array_map('trim', explode('-', $request->date)), 'strlen');
        // dd($parseDate);
        $validatedData['start_date'] = Carbon::createFromFormat('d/m/Y', $parseDate[0]);
        $validatedData['end_date'] = Carbon::createFromFormat('d/m/Y', $parseDate[1]);
        $validatedData = $validatedData + $request->all();

        if (! $event->update($validatedData)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$event->name' GAGAL diupdate!"]);
        }

        // update photo
        if ($request->hasFile('photo')) {
            // inject data attributes to update photo
            $event->photo = uploadEventPhoto($request->photo, $event->id);
            $event->save();
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '$event->name' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} '$event->name' telah diupdate.");
    }

    public function destroy(Request $request, Event $event)
    {
        if (! $event->delete()) {
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
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah dihapus.");
    }

    public function import(Request $request, Event $event)
    {
        $this->globalData = [
            'pageTitle' => "Import Peserta {$this->moduleName} {$event->name}",
            'event' => $event,
            'id' => $event->id,
            'moduleName' => 'Peserta',
        ] + $this->globalData;

        // dd($event, $this->globalData);

        if (request()->ajax()) {
            return view($this->baseViewPath.'_import_form', $this->globalData)->render();
        }

        // dd($this->globalData);
        return view($this->baseViewPath.'import', $this->globalData);
    }

    public function importProcess(Request $request, Event $event)
    {
        $rules = [
            'file' => 'required',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        if ($request->has('delete_old_data')) {
            Schema::disableForeignKeyConstraints();

            $event->eventStages()->delete();

            if ($request->has('delete_all_data') && auth()->user()->isSuperuser()) {
                EventSessionParticipant::truncate();
                EventSession::truncate();
                EventStage::truncate();
                // Event::truncate();
                MasterMatchCategory::truncate();
                MasterMatchType::truncate();
                MasterSchool::truncate();
                MasterParticipant::truncate();
            }

            Schema::enableForeignKeyConstraints();
        }

        $import = false;
        DB::beginTransaction();
        try {
            if (! $request->has('multiple_sheets')) {
                Excel::import(new EventParticipantSingleSheetImport($event->id), request()->file('file'));
            } else {
                Excel::import(new EventParticipantMultipleSheetImport($event->id, $request->total_sheets), request()->file('file'));
            }
            $import = true;

            DB::commit();
        } catch (\Exception $e) {
            Log::info('Error Import File from Excel.');
            Log::info($e);
            DB::rollBack();
        }

        if (! $import) {
            return back()
                ->withInput()
                ->withErrors(["Import Peserta {$this->moduleName} '$event->name' GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("Import Peserta {$this->moduleName} '$event->name' telah disimpan!");
    }

    public function downloadEventBook(Request $request, Event $event)
    {
        $eventStages = $event->eventStages()
            ->orderBy('order_number', 'asc')
            ->orderBy('number', 'asc')
            ->get();

        $eventStages->load([
            'masterMatchType',
            'masterMatchCategory',
            'eventSessions' => function ($q) {
                $q->withCount('eventSessionParticipants')
                    ->orderBy('session', 'asc');
            },
            'eventSessions.eventSessionParticipants' => function ($q) {
                $q->orderBy('track', 'asc');
            },
            'eventSessions.eventSessionParticipants.masterParticipant',
            'eventSessions.eventSessionParticipants.masterParticipant.masterSchool',
        ]);
        // dd($eventStages[0]->toArray());

        $filename = $event->slug.'-event';

        if ($request->filled('view_only')) {
            return view($this->baseViewPath.'exports.event.pdf-event-book', [
                'event' => $event,
                'eventStages' => $eventStages,
            ]);
        }

        $headerHtml = view($this->baseViewPath.'exports.event.pdf-event-header', compact('event'))->render();
        $footerHtml = view($this->baseViewPath.'exports.event.pdf-event-footer', compact('event'))->render();

        $pdf = SnappyPdf::loadView($this->baseViewPath.'exports.event.pdf-event-book', [
            'event' => $event,
            'eventStages' => $eventStages,
        ]);
        $pdf->setPaper('a4');
        // $pdf->setOption('enable-javascript', true);
        // $pdf->setOption('javascript-delay', 5000);
        $pdf->setOption('enable-smart-shrinking', true);
        $pdf->setOption('no-stop-slow-scripts', true);
        $pdf->setOption('margin-top', '40mm');
        $pdf->setOption('margin-bottom', '20mm');
        $pdf->setOption('header-html', $headerHtml);
        $pdf->setOption('footer-html', $footerHtml);

        // if (!app()->environment('production')) {
        return $pdf->inline($filename.'.pdf');
        // }

        // return $pdf->download($filename . '.pdf');
    }

    public function downloadReportBook(Request $request, Event $event)
    {
        $eventStages = $event->eventStages()
            ->orderBy('order_number', 'asc')
            ->orderBy('number', 'asc')
            ->get();

        $eventStages->load([
            'masterMatchType',
            'masterMatchCategory',
            // 'eventSessions',
            'eventSessionParticipants' => function ($q) {
                $q->orderBy('disqualification', 'asc')
                    ->orderBy('point', 'asc');
            },
            'eventSessionParticipants.masterParticipant',
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
        $pdf->setPaper('a4');
        // $pdf->setOption('enable-javascript', true);
        // $pdf->setOption('javascript-delay', 5000);
        $pdf->setOption('enable-smart-shrinking', true);
        $pdf->setOption('no-stop-slow-scripts', true);
        $pdf->setOption('margin-top', '40mm');
        $pdf->setOption('margin-bottom', '20mm');
        $pdf->setOption('header-html', $headerHtml);
        $pdf->setOption('footer-html', $footerHtml);

        // if (!app()->environment('production')) {
        return $pdf->inline($filename.'.pdf');
        // }

        // return $pdf->download($filename . '.pdf');
    }
}
