<?php

namespace App\Exports;

use App\Models\Event;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EventBookExport implements WithMultipleSheets
{
    use Exportable;

    protected $event;

    protected $number;

    protected $eventNumbers;

    public function __construct(Event $event, Collection $eventNumbers)
    {
        $this->event = $event;
        $this->eventNumbers = $eventNumbers;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->eventNumbers as $eventBook) {
            $sheets[] = new EventBookPerNumberSheet($this->event, $eventBook->sheetName, $eventBook, $this->eventNumbers);
        }

        return $sheets;
    }
}
