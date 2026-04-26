<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Category;

use App\Models\Event;
use App\Models\MasterMatchCategory;
use Illuminate\Http\Request;

class UpdateTypeController extends BaseController
{
    public function __invoke(Request $request, Event $event, MasterMatchCategory $masterMatchCategory)
    {
        $attributes = [];
        if ($request->input('types')) {
            foreach ($request->input('types') as $typeId) {
                $attributes[$typeId] = [
                    'master_match_category_id' => $masterMatchCategory->id,
                ];
            }
        }
        if (! $event->categoryTypes()->withPivotValue('master_match_category_id', $masterMatchCategory->id)->sync($attributes)) {
            return back()
                ->withInput()
                ->withErrors([__('Gaya')." Kategori {$masterMatchCategory->name} {$this->parentModuleName} '$event->name' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess(__('Gaya')." Kategori {$masterMatchCategory->name} {$this->parentModuleName} '$event->name' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', $event->id)
            ->withSuccess(__('Gaya')." Kategori {$masterMatchCategory->name} {$this->parentModuleName} '$event->name' telah diupdate.");
    }
}
