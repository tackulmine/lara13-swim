<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Category;

use App\Models\Event;

class IndexController extends BaseController
{
    public function __invoke(Event $event)
    {
        $categories = $event->categories()
            ->orderBy('ordering')
            ->orderBy('name')
            ->get();

        $breadcrumbs = [
            route($this->parentRouteName.'index') => $this->parentModuleName,
            '' => $this->moduleName,
        ];

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName} {$event->name}",
            'event' => $event,
            'categories' => $categories,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
