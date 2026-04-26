<?php

namespace App\Http\Controllers\Dashboard\Admin\External\Rank;

use App\Imports\ExternalAthleteBestTimeMultipleSheetImport;
use App\Imports\ExternalAthleteBestTimeSingleSheetImport;
use App\Models\ExternalAthleteBestTime;
use App\Models\ExternalSwimmingAthlete;
use App\Models\ExternalSwimmingClub;
use App\Models\ExternalSwimmingEvent;
use App\Models\ExternalSwimmingStyle;
use Illuminate\Http\Request;
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
            'file' => 'File Excel',
        ];
    }

    public function __invoke(Request $request)
    {
        $rules = [
            'file' => 'required|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel',
        ];

        $request->validate($rules, $this->customMessages, $this->customAttributes);

        if ($request->filled('delete_all_data') && auth()->user()->isSuperuser()) {
            Schema::disableForeignKeyConstraints();

            ExternalAthleteBestTime::truncate();
            ExternalSwimmingAthlete::truncate();
            ExternalSwimmingClub::truncate();
            ExternalSwimmingEvent::truncate();
            ExternalSwimmingStyle::truncate();

            Schema::enableForeignKeyConstraints();
        }

        $import = false;
        DB::beginTransaction();
        try {
            if (! $request->has('multiple_sheets')) {
                Excel::import(new ExternalAthleteBestTimeSingleSheetImport, request()->file('file'));
            } else {
                Excel::import(new ExternalAthleteBestTimeMultipleSheetImport($request->total_sheets), request()->file('file'));
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
                ->withErrors(["Import {$this->moduleName} GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("Import {$this->moduleName} telah disimpan!");
    }
}
