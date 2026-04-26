<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Gaya;

use App\Models\MasterChampionshipGaya;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
    public function __invoke(Request $request)
    {
        $gayaTable = (new MasterChampionshipGaya)->getTable();
        $rules = [
            'name' => 'required|unique:'.$gayaTable.'|min:2|max:100',
        ];

        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        // $validatedData['created_by'] = auth()->id();

        $gaya = MasterChampionshipGaya::create($request->only('name'));
        if (! $gaya) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} Baru GAGAL disimpan!"]);
        }

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("{$this->moduleName} '{$gaya->name}' telah disimpan!");
    }
}
