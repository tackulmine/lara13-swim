<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Category;

use App\Models\Event;
use App\Models\MasterMatchCategory;

class EditTypeController extends BaseController
{
    public function __invoke(Event $event, MasterMatchCategory $masterMatchCategory)
    {
        $typeIds = $event->categoryTypes()
            ->where('master_match_category_id', $masterMatchCategory->id)
            ->pluck('master_match_type_id')
            ->toArray();

        $types = $event->types()
            ->orderBy('ordering')
            ->orderBy('name')
            ->pluck('name', 'id');

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$event->name}",
            'event' => $event,
            'masterMatchCategory' => $masterMatchCategory,
            'typeIds' => $typeIds,
            'types' => $types,
            'id' => $event->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_type-form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'type-edit', $this->globalData);
    }
}
