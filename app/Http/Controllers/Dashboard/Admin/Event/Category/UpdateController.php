<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Category;

use App\Http\Requests\Dashboard\Event\UpdateEventCategoryRequest;
use App\Models\Event;

class UpdateController extends BaseController
{
    public function __invoke(UpdateEventCategoryRequest $request, Event $event)
    {
        if (! $event->categories()->sync($request->input('categories'))) {
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
