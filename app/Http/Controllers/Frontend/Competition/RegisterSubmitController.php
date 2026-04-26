<?php

namespace App\Http\Controllers\Frontend\Competition;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRegistrationRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterSubmitController extends Controller
{
    public function __invoke(EventRegistrationRequest $request, string $eventSlug)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();
        if (! $event->is_reg) {
            abort(404);
        }

        // REGISTRATION IS OVER
        if (! empty($event->reg_end_date) && now() >= $event->reg_end_date->toDateString() && ! auth()->check()) {
            return back()
                ->withErrors(['Pendaftaran telah ditutup!']);
        }
        // REGISTRATION MAX QUOTA
        if (! empty($event->reg_quota) && $event->eventRegistrations()->count() >= $event->reg_quota) {
            return back()
                ->withErrors(['Pendaftaran telah mencapai maksimum kuota!']);
        }

        // EVENT ON GOING
        if (now() >= $event->start_date->toDateString()) {
            return back()
                ->withErrors(['Kompetisi telah/sedang berlangsung!']);
        }

        // EVENT IS OVER
        if ($event->eventStages() && $event->eventStages()->whereCompleted(1)->exists()) {
            return back()
                ->withErrors(['Kompetisi telah selesai!']);
        }

        $data = [];
        $data['event'] = $event;

        // === PROGRESS

        DB::beginTransaction();

        try {
            $masterSchool = MasterSchool::where('name', strSquish($request->input('school')))
                ->lockForUpdate()
                ->first();

            if (! $masterSchool) {
                $masterSchool = MasterSchool::create([
                    'name' => strtoupper(strSquish($request->input('school'))),
                ]);
            }

            $atletName = strtoupper(strSquish($request->input('name')));
            $gender = $request->input('gender', 'L');
            $birthYear = intval($request->input('birth_year'));

            $masterParticipant = MasterParticipant::where([
                ['master_school_id', '=', $masterSchool->id],
                ['name', '=', $atletName],
                ['gender', '=', $gender],
                ['birth_year', '=', $birthYear],
            ])
                ->lockForUpdate()
                ->first();

            if (! $masterParticipant) {
                $masterParticipant = MasterParticipant::create([
                    'master_school_id' => $masterSchool->id,
                    'name' => $atletName,
                    'gender' => $gender,
                    'birth_year' => $birthYear,
                ]);
            }

            $eventRegistration = EventRegistration::where([
                ['event_id', '=', $event->id],
                ['master_participant_id', '=', $masterParticipant->id],
                ['master_match_category_id', '=', intval($request->input('category'))],
            ])
                ->lockForUpdate()
                ->first();

            if (! $eventRegistration) {
                $eventRegistration = EventRegistration::create([
                    'event_id' => $event->id,
                    'master_participant_id' => $masterParticipant->id,
                    'master_match_category_id' => intval($request->input('category')),
                    'coach_name' => strtoupper(strSquish($request->input('coach_name'))),
                    'coach_phone' => cleanPhoneNumber($request->input('coach_phone')),
                ]);
            }

            $updatedData = [];
            // update school_certificate file
            if ($request->hasFile('school_certificate')) {
                $updatedData['school_certificate'] = uploadEventRegistrationFile($request->school_certificate, 'school_certificate-'.$eventRegistration->id);
            }
            // update birth_certificate file
            if ($request->hasFile('birth_certificate')) {
                $updatedData['birth_certificate'] = uploadEventRegistrationFile($request->birth_certificate, 'birth_certificate-'.$eventRegistration->id);
            }
            // update photo file
            if ($request->hasFile('photo')) {
                $updatedData['photo'] = uploadEventRegistrationFile($request->photo, 'photo-'.$eventRegistration->id);
            }
            $eventRegistration->update($updatedData);

            // sync type/gaya
            $styleWithPivot = [];
            foreach ($request->input('style') as $index => $style) {
                $styleWithPivot[$style] = [
                    'is_no_point' => $request->input('style_value.'.$index) ? false : true,
                    'point_text' => $request->input('style_value.'.$index) ?: null,
                    'point' => $request->input('style_value.'.$index) ? parsePointToInt($request->input('style_value.'.$index)) : null,
                ];
            }
            // $eventRegistration->types()->syncWithoutDetaching($styleWithPivot);
            $eventRegistration->types()->sync($styleWithPivot);

            DB::commit();
        } catch (\Throwable $th) {
            // throw $th;
            Log::error('Event Registration ERROR: ');
            Log::error($th->getMessage());
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['Registrasi Kompetisi GAGAL!']);
        }

        $minutes = 120;
        if ($event->eventSpecialTypes->isNotEmpty()) {
            Cookie::queue('registration_as', $request->input('register_as'), $minutes);
        }
        Cookie::queue('registration_school', $request->input('school'), $minutes);
        Cookie::queue('registration_coach_name', $request->input('coach_name'), $minutes);
        Cookie::queue('registration_coach_phone', $request->input('coach_phone'), $minutes);

        return back()
            ->withSuccess('Registrasi Kompetisi berhasil!');
    }
}
