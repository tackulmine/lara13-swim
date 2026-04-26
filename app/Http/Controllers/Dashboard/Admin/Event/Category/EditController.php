<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Category;

use App\Models\Event;
use App\Models\MasterMatchCategory;

class EditController extends BaseController
{
    public function __invoke(Event $event)
    {
        $categories = MasterMatchCategory::orderBy('name')
            ->pluck('name', 'id');

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$event->name}",
            'event' => $event,
            'categories' => $categories,
            'id' => $event->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }
}
