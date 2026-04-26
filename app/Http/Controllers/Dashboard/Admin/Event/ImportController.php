<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImportController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
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

        $this->globalData = [
            'pageTitle' => "Import Peserta {$this->moduleName} {$event->name}",
            'event' => $event,
            'id' => $event->id,
            'moduleName' => 'Peserta',
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_import_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'import', $this->globalData);
    }
}
