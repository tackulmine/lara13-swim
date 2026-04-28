<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Type;

use App\Http\Requests\Dashboard\Event\UpdateEventTypeRequest;
use App\Models\Event;

class UpdateController extends BaseController
{
    public function __invoke(UpdateEventTypeRequest $request, Event $event)
    {
        if (! $event->types()->sync($request->input('types', []))) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$event->name' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '$event->name' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', $event->id)
            ->withSuccess("{$this->moduleName} '$event->name' telah diupdate.");
    }
}
