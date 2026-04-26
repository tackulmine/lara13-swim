<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Gaya;

use App\Models\MasterChampionshipGaya;
use Illuminate\Http\Request;

class UpdateController extends BaseController
{
    public function __invoke(Request $request, MasterChampionshipGaya $gaya)
    {
        $gayaTable = (new MasterChampionshipGaya)->getTable();
        $rules = [
            'name' => 'filled|unique:'.$gayaTable.',name,'.$gaya->id.',id|min:2|max:100',
        ];

        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        // $validatedData['updated_by'] = auth()->id();

        // IF FAILED
        if (! $gaya->update($request->only('name'))) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '{$gaya->name}' GAGAL diupdate!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '{$gaya->name}' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} '{$gaya->name}' telah diupdate.");
    }
}
