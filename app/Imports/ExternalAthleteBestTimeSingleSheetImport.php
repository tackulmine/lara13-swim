<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExternalAthleteBestTimeSingleSheetImport implements SkipsUnknownSheets, WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new ExternalAthleteBestTimesImport,
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}
