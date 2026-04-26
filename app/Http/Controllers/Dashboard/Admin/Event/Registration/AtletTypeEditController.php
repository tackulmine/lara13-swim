<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Registration;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class AtletTypeEditController extends BaseController
{
    public function __invoke(Request $request, Event $event, EventRegistration $eventRegistration)
    {
        $breadcrumbs = [
            route($this->parentRouteName.'index') => $this->parentModuleName,
            route($this->baseRouteName.'atlet', $event) => $this->moduleName,
            '' => 'Edit Gaya Atlet',
        ];

        $eventRegistrationTypes = $event->categoryTypes()
            ->where('master_match_category_id', $eventRegistration->master_match_category_id)
            ->where(function ($q) use ($eventRegistration) {
                $q->where('name', 'like', '%'.(optional($eventRegistration->masterParticipant)->gender == 'male' ? 'PA' : 'PI'))
                    ->orWhere('name', 'like', '%'.(optional($eventRegistration->masterParticipant)->gender == 'male' ? 'PUTRA' : 'PUTRI'));
            })
            ->orderBy('name')
            ->pluck('name', 'id');

        $categories = $event->categories()
            ->orderBy('ordering', 'asc')
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');

        $currentTypeIds = $eventRegistration->types()->pluck('master_match_type_id')->toArray();
        $currentTypes = $eventRegistration->types;
        // dd($currentTypes->toArray());
        $additionalValues = null;
        foreach ($currentTypes as $currentType) {
            $additionalValues[$currentType->id] = $currentType->pivot->point_text;
        }

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$this->parentModuleName} - {$event->name}",
            'event' => $event,
            'id' => $eventRegistration->id,
            'eventRegistration' => $eventRegistration,
            'currentTypeIds' => $currentTypeIds,
            'currentTypes' => $currentTypes,
            'additionalValues' => $additionalValues,
            'eventRegistrationTypes' => $eventRegistrationTypes,
            'categories' => $categories,
            'breadcrumbs' => $breadcrumbs,
        ] + $this->globalData;

        // logger()->info('currentTypeIds: '.$eventRegistration->types()->pluck('master_match_type_id')->implode(', '));

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form-atlet-type-edit', $this->globalData)->render();
        }

        return view($this->baseViewPath.'atlet-type-edit', $this->globalData);
    }
}
