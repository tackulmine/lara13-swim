<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EventParticipantSingleSheetImport implements SkipsUnknownSheets, WithMultipleSheets
{
    private $eventId;

    public function __construct(int $eventId)
    {
        $this->eventId = intval($eventId);
    }

    public function sheets(): array
    {
        return [
            new EventParticipantsImport($this->eventId),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}
