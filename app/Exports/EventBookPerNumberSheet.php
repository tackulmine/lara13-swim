<?php

namespace App\Exports;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class EventBookPerNumberSheet implements FromView, WithTitle
{
    protected $event;

    protected $sheetName;

    protected $eventBook;

    protected $eventNumbers;

    public function __construct(Event $event, string $sheetName, $eventBook, Collection $eventNumbers)
    {
        $this->event = $event;
        $this->sheetName = $sheetName;
        $this->eventBook = $eventBook;
        $this->eventNumbers = $eventNumbers;
    }

    public function view(): View
    {
        return view('dashboard.admin.event.book.exports.swimming-sessions', [
            'event' => $this->event,
            'eventBook' => $this->eventBook,
            'eventNumbers' => $this->eventNumbers,
        ]);
    }

    public function title(): string
    {
        return $this->sheetName;
    }
}
