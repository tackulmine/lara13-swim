<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Libraries\FormFields;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CreateController extends BaseController
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
        $formFields = new FormFields($event);
        $formFields = $formFields->generateForm();
        $formFields->photo_url = $event->photo_url;
        $formFields->photo_right_url = $event->photo_right_url;
        $formFields->preview_photo = $event->preview_photo;
        $formFields->preview_photo_right = $event->preview_photo_right;

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'event' => $formFields,
            'eventCatagories' => [],
            'selectedRegCatStyleMinValues' => [],
        ] + $this->globalData;

        if ($request->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }
}
