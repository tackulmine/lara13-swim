<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Type;

use App\Models\Event;
use App\Models\MasterMatchType;

class EditController extends BaseController
{
    public function __invoke(Event $event)
    {
        $types = MasterMatchType::orderBy('name')
            ->pluck('name', 'id');

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$event->name}",
            'event' => $event,
            'types' => $types,
            'id' => $event->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }
}
