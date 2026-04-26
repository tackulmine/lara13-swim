<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Registration;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\MasterMatchType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AtletTypeUpdateController extends BaseController
{
    public function __invoke(Request $request, Event $event, EventRegistration $eventRegistration)
    {
        $rules = [
            'master_match_category_id' => [
                'required',
                'integer',
                // 'exists:event_category,master_match_category_id',
                Rule::exists('event_category', 'master_match_category_id')
                    ->where('event_id', $event->id),
            ],
            'coach_name' => ['required', 'string', 'max:100'],
            // 'coach_phone' => ['required', 'string', 'max:20', 'regex:/^(\+62|0)[0-9]{10}$/'],
            'coach_phone' => ['required', 'string'],
            'style' => ['required', 'array'],
            'style.*' => [
                'integer',
                // 'exists:'.(new MasterMatchType)->getTable().',id',
                Rule::exists('event_category_type', 'master_match_type_id')
                    ->where('master_match_category_id', $request->master_match_category_id)
                    ->where('event_id', $event->id),
            ],
        ];
        $messages = [];
        $customAttributes = [
            // 'name' => 'Nama Lengkap',
            // 'birth_year' => 'Tahun Lahir',
            // 'gender' => __('Gender'),
            'master_match_category_id' => __('Kategori'),
            // 'school' => __('Sekolah'),
            // 'style' => __('Gaya'),
            // 'school_certificate' => 'Surat Keterangan Sekolah',
            // 'birth_certificate' => 'Akta Kelahiran',
            // 'photo' => 'Foto Atlet',
            'coach_name' => 'Nama Pelatih',
            'coach_phone' => 'No. HP/WA Pelatih',
        ];

        $validatedData = $request->validate($rules, $messages, $customAttributes);

        // $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        // if ($validator->fails()) {
        //     dd($validator->messages());
        // }

        DB::beginTransaction();

        try {
            // update registration field
            $eventRegistration->update(
                array_merge(
                    $validatedData,
                    [
                        'coach_name' => strtoupper(strSquish($request->coach_name)),
                        'coach_phone' => cleanPhoneNumber($request->coach_phone),
                    ],
                )
            );
            // sync type/gaya
            // $eventRegistration->types()->sync($request->input('type_ids'));
            $styleWithPivot = [];
            foreach ($request->input('style') as $index => $style) {
                $styleWithPivot[$style] = [
                    'is_no_point' => $request->input('style_value.'.$index) ? false : true,
                    'point_text' => $request->input('style_value.'.$index) ?: null,
                    'point' => $request->input('style_value.'.$index) ? parsePointToInt($request->input('style_value.'.$index)) : null,
                ];
            }
            $eventRegistration->types()->sync($styleWithPivot);

            DB::commit();
        } catch (\Throwable $th) {
            // throw $th;
            Log::error('Event Registration Type Sync ERROR: ');
            Log::error($th->getMessage());
            DB::rollBack();

            if (request()->ajax()) {
                return response()->json(['message' => "Update Gaya '{$eventRegistration->masterParticipant->name}' GAGAL!"], 200);
            }

            return back()
                ->withInput()
                ->withErrors(['Update Gaya GAGAL!']);
        }

        if (request()->ajax()) {
            return response()->json(['message' => "Update Gaya '{$eventRegistration->masterParticipant->name}' telah BERHASIL!"], 200);
        }

        return back()
            ->withSuccess("Update Gaya '{$eventRegistration->masterParticipant->name}' telah BERHASIL!");
    }
}
