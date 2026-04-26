<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Imports\EventParticipantMultipleSheetImport;
use App\Imports\EventParticipantSingleSheetImport;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use App\Models\MasterMatchCategory;
use App\Models\MasterMatchType;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class ImportProcessController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

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

    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request, Event $event)
    {
        // check if event/stage/session has been completed
        if ($event->completed
            // || $event->eventSessions->where('completed', true)->count()
        ) {
            return redirect()
                ->route($this->baseRouteName.'index')
                ->withErrors(["Import Peserta {$this->moduleName} '$event->name' TIDAK diijinkan!"]);
        }

        $rules = [
            'file' => 'required',
        ];
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        if ($request->has('delete_old_data')) {
            $event->eventStages()->delete();

            if ($request->has('delete_all_data') && auth()->user()->isSuperuser()) {
                Schema::disableForeignKeyConstraints();

                EventSessionParticipant::truncate();
                EventSession::truncate();
                EventStage::truncate();
                // Event::truncate();
                MasterMatchCategory::truncate();
                MasterMatchType::truncate();
                MasterSchool::truncate();
                MasterParticipant::truncate();

                Schema::enableForeignKeyConstraints();
            }
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

        // AUTO REMOVE participants with 'none' name!
        $participants = MasterParticipant::where('name', 'like', '%none%');
        if ((clone $participants)->count()) {
            $participants->delete();
        }

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("Import Peserta {$this->moduleName} '$event->name' telah disimpan!");
    }
}
