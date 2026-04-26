<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Type;

use App\Models\Event;

class IndexController extends BaseController
{
    public function __invoke(Event $event)
    {
        $types = $event->types()
            ->orderBy('ordering')
            ->orderBy('name')
            ->get();

        $types->load('eventCategories');

        $breadcrumbs = [
            route($this->parentRouteName.'index') => $this->parentModuleName,
            '' => $this->moduleName,
        ];

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName} {$event->name}",
            'event' => $event,
            'types' => $types,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
