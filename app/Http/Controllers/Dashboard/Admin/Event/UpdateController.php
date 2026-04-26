<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class UpdateController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->generateFormAttributes();
    }

    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request, Event $event)
    {
        if (! auth()->user()->hasRole('coach')
            and $event->created_by != auth()->id()
        ) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak memiliki akses.'], 403);
            }

            abort(403, 'Tidak memiliki akses.');
        }

        $rules = [
            'name' => 'filled|unique:'.Event::table().',name,'.$event->id.',id|max:255',
            'slug' => 'sometimes|max:255',
            // 'address' => 'filled',
            'location' => 'filled',
            'date' => 'filled',
        ];
        if ($request->hasFile('photo')) {
            $rules = [
                'photo' => [
                    'image',
                    'mimes:jpeg,jpg,png,gif',
                    'max:2048', // 2MB
                ],
            ] + $rules;
        }
        if ($request->hasFile('photo_right')) {
            $rules = [
                'photo_right' => [
                    'image',
                    'mimes:jpeg,jpg,png,gif',
                    'max:2048', // 2MB
                ],
            ] + $rules;
        }
        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);

        $parseDate = array_filter(array_map('trim', explode('-', $request->date)), 'strlen');
        $validatedData['slug'] = Str::slug($validatedData['slug']);
        $validatedData['start_date'] = Carbon::createFromFormat('d/m/Y', $parseDate[0]);
        $validatedData['end_date'] = Carbon::createFromFormat('d/m/Y', $parseDate[1]);
        $validatedData['is_external'] = ! empty($request->is_external) ? 1 : 0;
        $validatedData['is_has_mix_gender'] = ! empty($request->is_has_mix_gender) ? 1 : 0;
        $validatedData['is_reg'] = ! empty($request->is_reg) ? 1 : 0;
        $validatedData['reg_end_date'] = ($request->is_reg && $request->reg_end_date)
        ? Carbon::createFromFormat('d/m/Y', $request->reg_end_date)
        : null;
        $validatedData['reg_quota'] = $validatedData['is_reg'] ? $request->reg_quota : null;
        $validatedData = $validatedData + $request->all();

        // dd($validatedData);

        if (! $event->update($validatedData)) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$event->name' GAGAL diupdate!"]);
        }

        // update photo
        if ($request->hasFile('photo')) {
            // inject data attributes to update photo
            $event->photo = uploadEventPhoto($request->photo, $event->id);
        }
        if ($request->hasFile('photo_right')) {
            // inject data attributes to update photo
            $event->photo_right = uploadEventPhoto($request->photo_right, $event->id.'-right');
        }
        if ($request->hasFile('photo') || $request->hasFile('photo_right')) {
            $event->save();
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '$event->name' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} '$event->name' telah diupdate.");
    }
}
