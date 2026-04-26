<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Libraries\FormFields;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EditController extends BaseController
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
        if (
            ! auth()->user()->hasRole('coach')
            and $event->created_by != auth()->id()
        ) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        // dd($event->toArray());
        $formFields = new FormFields($event);
        $formFields = $formFields->generateForm();
        $formFields->photo_url = $event->photo_url;
        $formFields->photo_right_url = $event->photo_right_url;
        $formFields->preview_photo = $event->preview_photo;
        $formFields->preview_photo_right = $event->preview_photo_right;
        if (! $event->completed) {
            $formFields->preview_fancy_qr_code = $event->preview_fancy_qr_code;
        }

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$event->name}",
            'event' => $formFields,
            'eventCatagories' => $event->categories->pluck('name', 'id'),
            'selectedRegCatStyleMinValues' => $event->reg_cat_style_min,
            'id' => $event->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        // dd($this->globalData);
        return view($this->baseViewPath.'edit', $this->globalData);
    }
}
