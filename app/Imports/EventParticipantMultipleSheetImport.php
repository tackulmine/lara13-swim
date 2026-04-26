<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EventParticipantMultipleSheetImport implements SkipsUnknownSheets, WithMultipleSheets
{
    private $eventId;

    private $sheetTotal;

    public function __construct(int $eventId, int $sheetTotal)
    {
        $this->eventId = intval($eventId);
        $this->sheetTotal = intval($sheetTotal);
    }

    public function sheets(): array
    {
        $temps = [];
        for ($i = 0; $i < $this->sheetTotal; $i++) {
            $temps[$i] = new FirstEventParticipantSheetImport($this->eventId, $i);
        }

        return $temps;
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}
