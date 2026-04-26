<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Book;

use App\Models\Event;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    use EventBookTrait;

    public function __invoke(Request $request, Event $event)
    {
        $this->generateEventNumbers($event, $request->boolean('reset_all'));
        $eventNumbers = $this->getEventNumbers($event);

        return view($this->baseViewPath.'index', [
            'pageTitle' => "Daftar {$this->moduleName} {$event->name}",
            'event' => $event,
            'eventNumbers' => $eventNumbers,
            'breadcrumbs' => [
                route($this->parentRouteName.'index') => $this->parentModuleName,
                '' => $this->moduleName,
            ],
        ] + $this->globalData);
    }
}
