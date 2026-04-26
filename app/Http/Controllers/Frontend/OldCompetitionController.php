<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRegistrationRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use App\Models\MasterMatchCategory;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OldCompetitionController extends Controller
{
    public function detail(Request $request, string $eventSlug)
    {
        $event = Event::with([
            'eventStages',
            'eventStages.masterMatchCategory',
            'eventStages.masterMatchType',
        ])
            ->whereSlug($eventSlug)
            ->firstOrFail();

        $data = [];
        $data['event'] = $event;
        // dump($event->toArray()); die;

        $data['eventStage'] = $eventStage = $event->eventStages()
            ->where('completed', false)
            ->orderBy('completed')
            ->orderBy('order_number')
            ->orderBy('number')
            ->first();

        // check if event stage has been all completed n get the latest one of the event stage
        if (auth()->check() && ! $eventStage) {
            $data['eventStage'] = $eventStage = $event->eventStages()
                ->where('completed', true)
                ->orderByDesc('number')
                ->orderByDesc('order_number')
                ->first();
        }

        // $data['eventSession'] = $eventSession = EventSession::whereHas('eventStage', function ($q) use ($eventStage) {
        //     $q->where('event_stage_id', $eventStage->id);
        // })

        // dd($event->eventParticipants()->has('masterParticipant')->pluck('track')->unique()->toArray());
        // $data['minTrack'] = $data['maxTrack'] = 1; // force to 1
        // if ($event->start_track_number != 1) {
        //     $data['minTrack'] = 0;
        // }

        // ONLY BY REQUEST CUSTOM HALF AND FULL TRACK
        // if (! empty($eventStage->eventSessionParticipants)) {
        //     $cacheKey = '|event|'.$event->id.'|event-stage|'.$eventStage->id.'|participants|max-track|';
        //     $eventStageMaxTrack = cache()->remember($cacheKey, config('cache.ttl'), function () use ($eventStage) {
        //         return $eventStage
        //             ->eventSessionParticipants()
        //             ->pluck('track')
        //             ->unique()
        //             ->max();
        //     });
        //     $maxTrack = $eventStageMaxTrack;
        //     // if ($maxTrack < 5) {
        //     //     $maxTrack = 5;
        //     // }
        //     // if ($maxTrack > 5 and $maxTrack < 10) {
        //     //     $maxTrack = 10;
        //     // }
        //     $data['maxTrack'] = $maxTrack;
        // } elseif (! empty($eventStage->eventParticipants)) {
        // $tracks = $event->eventParticipants()->has('masterParticipant')->pluck('track')->unique();

        $cacheKey = '|event|'.$event->id.'|tracks|unique|';
        $tracks = cache()->remember($cacheKey, config('cache.ttl'), function () use ($event) {
            return $event->eventParticipants()->has('masterParticipant')->pluck('track')->unique();
        });
        $data['minTrack'] = $tracks->min();
        $data['maxTrack'] = $tracks->max();
        // }
        // dd($data['minTrack'], $data['maxTrack']);

        if (! empty($eventStage)) {
            $data['eventSession'] = $eventSession = $eventStage->eventSessions()
                ->orderBy('completed')
                ->orderBy('session')
                ->first();
            $data['currentEventSession'] = $eventSession;
            // dump($eventSession->toArray()); die;

            $data['eventSessions'] = EventSession::with([
                'eventSessionParticipants' => function ($q) {
                    $q->has('masterParticipant')
                        ->orderBy('track');
                },
                'eventSessionParticipants.masterParticipant',
                'eventSessionParticipants.masterParticipant.masterSchool',
            ])
                ->whereHas('eventStage', function ($q) use ($eventStage) {
                    $q->where('event_stage_id', $eventStage->id);
                })
                ->orderBy('session')
                ->get();
            // dump($data['eventSessions']->toArray()); die;

            $data['eventSessionParticipants'] = EventSessionParticipant::with([
                'eventSession',
                'masterParticipant',
                'masterParticipant.masterSchool',
            ])
                ->has('masterParticipant')
                ->whereHas('eventSession', function ($q) use ($eventSession) {
                    $q->where('event_session_id', $eventSession->id);
                    // ->whereCompleted(false);
                })
            // ->whereNull('point')
                ->orderBy('track')
                ->get();
            // dd($data['eventSessionParticipants']->toArray()); die;

            // $data['eventStagePointParticipants'] = EventSessionParticipant::with(['eventSession', 'masterParticipant', 'masterParticipant.masterSchool'])
            //     ->whereHas('eventSession.eventStage', function ($q) use ($eventStage) {
            //         $q->where('event_stage_id', $eventStage->id)
            //             ->whereCompleted(0);
            //     })
            //     ->whereNotNull('point')
            //     ->orderBy('track')
            //     ->get();
            // dump($data['eventStagePointParticipants']->toArray()); die;

            $data['eventStageRangkingParticipants'] = EventSessionParticipant::with([
                'eventSession',
                'masterParticipant',
                'masterParticipant.masterSchool',
            ])
                ->has('masterParticipant')
                ->whereHas('eventSession.eventStage', function ($q) use ($eventStage) {
                    $q->where('event_stage_id', $eventStage->id);
                    // ->whereCompleted(false);
                })
                ->whereNotNull('point')
                ->orderBy('disqualification')
                ->orderBy('dis_level')
                ->orderBy('point')
                ->orderBy('track')
                ->get();
            // dump($data['eventStageRangkingParticipants']->toArray()); die;
        }

        $data['pageTitle'] = $event->name;

        // excel values
        if ($request->filled('excel_values')) {
            $excelValues = collect($this->parseString($request->input('excel_values')));
            $data['excelValues'] = $excelValues->sortBy('lintasan')->keyBy('lintasan')->all();
            // dd($data['excelValues']);
        }

        return view('front.competition.detail', $data);
    }

    public function update(string $eventSlug, Request $request)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();

        // dd($request->toArray());
        $rules = [
            'participants.*.point' => 'required|size:8',
        ];
        $customMessages = [];
        $customAttributes = [];

        $participants = $request->get('participants', []);
        $customAttributes = collect($participants)
            ->mapWithKeys(function ($participants, $index) {
                $detail = EventSessionParticipant::with(['masterParticipant', 'masterParticipant.masterSchool'])->find($index);

                return [
                    "participants.{$index}.point" => 'Poin Peserta '.optional($detail->masterParticipant)->name.' ('.optional(optional($detail->masterParticipant)->masterSchool)->name.')',
                ];
            })
            ->merge($customAttributes)
            ->toArray();
        $validatedData = $request->validate($rules, $customMessages, $customAttributes);

        foreach ($request->participants as $id => $value) {
            // dd($id, $value);
            $attributes = [];
            $attributes['point_text'] = $value['point'];
            $attributes['point'] = parsePointToInt($value['point']);
            if (intval($request->participants[$id]['dis_level']) > 0) {
                $attributes['disqualification'] = true;
                $attributes['dis_level'] = $request->participants[$id]['dis_level'];
            }
            $attributes['updated_by'] = auth()->id();
            // dd($attributes);
            $eventSessionParticipant[] = EventSessionParticipant::where('id', $id)->update($attributes);
        }

        $eventStage = EventStage::whereId($request->event_stage)->firstOrFail();
        $eventSession = EventSession::whereId($request->event_session)->firstOrFail();

        $eventSession->load([
            'eventSessionParticipants' => function ($q) {
                $q->has('masterParticipant');
            },
        ]);

        if (count($eventSessionParticipant) == $eventSession->eventSessionParticipants->count()) {
            $eventSession->completed = 1;
            $eventSession->updated_by = auth()->id();
            $eventSession->save();

            // if ($eventSession->save()) {
            //     if ($eventStage->eventSessions()->whereCompleted(0)->doesntExist()) {
            //         $eventStage->completed = 1;
            //         $eventStage->updated_by = auth()->id();
            //         $eventStage->save();
            //     }
            // }

            return back()
                ->withSuccess('Data berhasil disimpan!');
        }

        return back()
            ->withInput()
            ->withErrors(['Data GAGAL disimpan!']);
    }

    private function parseString(string $str)
    {
        // 1    00:01:983   2
        // 2    00:08:354   3
        // 3    00:10:577   1

        $lines = explode("\n", trim($str));
        $parsed = [];

        foreach ($lines as $line) {
            // Bersihkan \r di setiap baris terlebih dahulu
            $line = str_replace("\r", '', $line);

            $columns = preg_split('/\t+/', $line);
            if (count($columns) === 3) {
                $parsed[] = [
                    'urutan' => $columns[0],
                    'waktu' => str_replace(':', '.', $columns[1]),
                    'lintasan' => $columns[2],
                ];
            }
        }

        return $parsed;
    }

    public function complete(string $eventSlug, Request $request)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();
        $eventStage = EventStage::whereId($request->event_stage)->firstOrFail();

        if ($eventStage->eventSessions()->whereCompleted(0)->doesntExist()) {
            $eventStage->completed = 1;
            $eventStage->updated_by = auth()->id();
            if ($eventStage->save()) {
                return back()
                    ->withSuccess('Acara sebelumnya telah Selesai!');
            }
        }

        return back()
            ->withInput()
            ->withErrors(['GAGAL memuat acara selanjutnya!']);
    }

    public function done(string $eventSlug, Request $request)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();

        if ($event->eventStages()->whereCompleted(0)->doesntExist()) {
            $event->completed = 1;
            $event->updated_by = auth()->id();
            if ($event->save()) {
                return back()
                    ->withSuccess('Kompetisi telah Selesai!');
            }
        }

        return back()
            ->withInput()
            ->withErrors(['GAGAL menyelesaikan Kompetisi!']);
    }

    public function register(string $eventSlug, Request $request)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();
        if (! $event->is_reg) {
            abort(404);
        }

        $schoolTable = (new MasterSchool)->getTable();
        $participantTable = (new MasterParticipant)->getTable();
        $eventRegistrationTable = (new EventRegistration)->getTable();

        $types = $event->types()
            ->orderBy('ordering', 'asc')
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');

        $categories = $event->categories()
            ->orderBy('ordering', 'asc')
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');

        $data = [];
        $data['pageTitle'] = $event->name;
        $data['event'] = $event;
        $data['types'] = $types;
        $data['categories'] = $categories;
        // gender options setting
        $genderOptions = [
            'male' => 'Laki-laki',
            'female' => 'Perempuan',
            // 'mix' => 'Mix',
        ];
        if ($event->is_has_mix_gender) {
            $genderOptions['mix'] = 'Mix';
        }
        $data['genderOptions'] = $genderOptions;
        // participants
        $participantOptions = ['' => '---'];
        if (old('school')) {
            $masterSchool = MasterSchool::where('name', old('school'))->first();
            // logger()->info('$masterSchool->name');
            if (! empty($masterSchool)) {
                // logger()->debug($masterSchool->name);

                $participants = $this->getMasterParticipants($event, $masterSchool);

                if (! empty($participants)) {
                    foreach ($participants as $participant) {
                        $participantOptions[$participant->name] = $participant->name.' ('.parseGenderAbbr($participant->gender).', '.($participant->birth_year ?? '-').')';
                    }
                }
            }
        }
        // dd($participantOptions);
        $data['participantOptions'] = $participantOptions;

        $registrationSchool = Cookie::get('registration_school');
        $registrationCoachName = Cookie::get('registration_coach_name');
        $registrationCoachPhone = Cookie::get('registration_coach_phone');

        // ### Get data from cookie if exists
        if (! empty($registrationSchool)
            && ! empty($registrationCoachName)
            // && ! empty($registrationCoachPhone)
        ) {
            $eventRegistrations = EventRegistration::query()
                ->select("{$eventRegistrationTable}.*", "{$participantTable}.name")
                ->join($participantTable, "{$participantTable}.id", '=', "{$eventRegistrationTable}.master_participant_id")
                ->join($schoolTable, "{$schoolTable}.id", '=', "{$participantTable}.master_school_id")
                ->where("{$eventRegistrationTable}.event_id", $event->id)
                ->where("{$schoolTable}.name", $registrationSchool)
                ->where("{$eventRegistrationTable}.coach_name", $registrationCoachName)
                // ->where("{$eventRegistrationTable}.coach_phone", cleanPhoneNumber($registrationCoachPhone))
                ->orderBy("{$participantTable}.name")
                ->orderBy("{$eventRegistrationTable}.id")
                ->get();

            $eventRegistrations->load([
                'event',
                'types' => function ($q) use ($event) {
                    $masterTypeIds = $event->types()->pluck('id');

                    $q->whereIn('master_match_type_id', $masterTypeIds);
                },
                'masterMatchCategory' => function ($q) use ($event) {
                    $masterCategoryIds = $event->categories()->pluck('id');

                    $q->whereIn('id', $masterCategoryIds);
                },
                'masterParticipant.masterSchool',
            ]);

            $data['eventRegistrations'] = $eventRegistrations;
        }

        // ### Check keyword filter and set cookie then redirect back
        if ($request->filled('search_school')
            && $request->filled('search_coach_name')
            // && $request->filled('search_coach_phone')
        ) {
            $masterSchool = MasterSchool::where('name', $request->input('search_school'))->first();

            if (empty($masterSchool)) {
                redirect()->route('competition.register', $event->slug)
                    ->withSearchError('Maaf, '.__('Sekolah').' TIDAK ditemukan!');
            }
            // $eventRegistrations = $event->eventRegistrations()
            //     ->where('coach_name', $request->input('search_coach_name'))
            //     ->where('coach_phone', cleanPhoneNumber($request->input('search_coach_phone')))
            //     ->orderBy('id');

            $schoolTable = (new MasterSchool)->getTable();
            $participantTable = (new MasterParticipant)->getTable();
            $eventRegistrationTable = (new EventRegistration)->getTable();
            $eventRegistrations = EventRegistration::query()
                ->select("{$eventRegistrationTable}.*", "{$participantTable}.name")
                ->join($participantTable, "{$participantTable}.id", '=', "{$eventRegistrationTable}.master_participant_id")
                ->join($schoolTable, "{$schoolTable}.id", '=', "{$participantTable}.master_school_id")
                ->where("{$eventRegistrationTable}.event_id", $event->id)
                ->where("{$schoolTable}.name", $request->input('search_school'))
                ->where("{$eventRegistrationTable}.coach_name", $request->input('search_coach_name'));
            // ->where("{$eventRegistrationTable}.coach_phone", cleanPhoneNumber($request->input('search_coach_phone')));

            if ($eventRegistrations->exists()) {
                // $eventRegistrations->load([
                //     'types',
                //     'masterMatchCategory',
                //     'masterParticipant.masterSchool',
                // ]);

                $minutes = 120;
                Cookie::queue('registration_school', $request->input('search_school'), $minutes);
                Cookie::queue('registration_coach_name', $request->input('search_coach_name'), $minutes);
                // Cookie::queue('registration_coach_phone', $request->input('search_coach_phone'), $minutes);
                Cookie::queue('registration_coach_phone', $eventRegistrations->first()->coach_phone, $minutes);

                return redirect()->route('competition.register', $event->slug)
                    ->withSearchSuccess('Registrasi Kompetisi ditemukan!');
            }

            return redirect()
                ->route('competition.register', $event->slug)
                ->withInput()
                ->withSearchError('Maaf, Registrasi Kompetisi TIDAK ditemukan!');
        }

        // ###  Download PDF registration list
        if ($request->filled('download')) {
            if (empty($registrationSchool)
                || empty($registrationCoachName)
                // || empty($registrationCoachPhone)
            ) {
                return redirect()->route('competition.register', $event->slug)
                    ->withErrors(['Sesimu berakhir, silakan input info pelatih untuk melihat list registrasi!']);
                exit;
            }

            $eventRegistrations = EventRegistration::query()
                ->select("{$eventRegistrationTable}.*", "{$participantTable}.name")
                ->join($participantTable, "{$participantTable}.id", '=', "{$eventRegistrationTable}.master_participant_id")
                ->join($schoolTable, "{$schoolTable}.id", '=', "{$participantTable}.master_school_id")
                ->where("{$eventRegistrationTable}.event_id", $event->id)
                ->where("{$schoolTable}.name", $registrationSchool)
                ->where("{$eventRegistrationTable}.coach_name", $registrationCoachName);

            $eventCategories = $event->categories->pluck('name', 'id');
            $individualCatIds = $eventCategories->filter(function ($value, $key) {
                return ! Str::contains($value, 'RELAY');
            })->keys()->all();
            $data['eventIndividualRegistrations'] = (clone $eventRegistrations)->whereIn('master_match_category_id', $individualCatIds)->get();

            $estafetCatIds = $eventCategories->filter(function ($value, $key) {
                return Str::contains($value, 'RELAY');
            })->keys()->all();
            $data['eventEstafetRegistrations'] = (clone $eventRegistrations)->whereIn('master_match_category_id', $estafetCatIds)->get();

            $filename = Str::slug($event->slug).'_'.Str::slug($registrationSchool).'_registration';

            if ($request->filled('view_only')) {
                return view('front.competition.print.pdf-register', $data);
            }

            $headerHtml = view('front.competition.print.pdf-register-header', $data)->render();
            $footerHtml = view('front.competition.print.pdf-register-footer', $data)->render();

            $pdf = SnappyPdf::loadView('front.competition.print.pdf-register', $data);
            $pdf->setPaper('folio');
            // $pdf->setOption('enable-javascript', true);
            // $pdf->setOption('javascript-delay', 5000);
            $pdf->setOption('enable-smart-shrinking', true);
            $pdf->setOption('no-stop-slow-scripts', true);
            $pdf->setOption('margin-top', '30mm');
            $pdf->setOption('margin-bottom', '15mm');
            $pdf->setOption('margin-left', '8mm');
            $pdf->setOption('margin-right', '8mm');
            $pdf->setOption('header-html', $headerHtml);
            $pdf->setOption('footer-html', $footerHtml);

            if (! app()->environment('production')) {
                return $pdf->inline($filename.'.pdf');
            }

            return $pdf->download($filename.'.pdf');
        }
        // ###  Show detail registration list
        if ($request->filled('show')) {
            if (empty($registrationSchool)
                || empty($registrationCoachName)
                || empty($registrationCoachPhone)
            ) {
                return redirect()->route('competition.register', $event->slug)
                    ->withErrors(['Sesimu berakhir, silakan input info pelatih untuk melihat list registrasi!']);
                exit;
            }

            return view('front.competition.print.register', $data);
        }

        return view('front.competition.register', $data);
    }

    public function registerSubmit(string $eventSlug, EventRegistrationRequest $request)
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
        Cookie::queue('registration_school', $request->input('school'), $minutes);
        Cookie::queue('registration_coach_name', $request->input('coach_name'), $minutes);
        Cookie::queue('registration_coach_phone', $request->input('coach_phone'), $minutes);

        return back()
            ->withSuccess('Registrasi Kompetisi berhasil!');
    }

    public function ajaxGetParticipants(string $eventSlug, Request $request)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();

        // participants
        $participantOptions = [];
        if ($request->filled('school')) {
            $masterSchool = MasterSchool::where('name', $request->school)->first();
            // logger()->info('$masterSchool->name');
            if (! empty($masterSchool)) {
                // logger()->debug($masterSchool->name);

                $participants = $this->getMasterParticipants($event, $masterSchool);
                foreach ($participants as $participant) {
                    $participantOptions[] = [
                        'id' => addslashes($participant->name),
                        'text' => addslashes($participant->name).' ('.parseGenderAbbr($participant->gender).', '.($participant->birth_year ?? '-').')',
                    ];
                }
            }
        }

        return response()->json(['results' => $participantOptions]);
    }

    public function ajaxGetParticipantDetail(string $eventSlug, Request $request)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();

        // participants
        $participant = collect();
        if ($request->filled('school') && $request->filled('name')) {
            $masterSchool = MasterSchool::where('name', $request->input('school'))->first();
            // logger()->info('$masterSchool->name');
            if (! empty($masterSchool)) {
                // logger()->debug($masterSchool->name);

                $participant = $this->getMasterParticipant($event, $masterSchool, $request);
            }
        }

        return response()->json($participant);
    }

    public function ajaxGetTypes(string $eventSlug, Request $request)
    {
        $event = Event::whereSlug($eventSlug)->firstOrFail();
        $category = MasterMatchCategory::findOrFail($request->input('category'));
        // logger()->info('category: '.$category->name);
        // logger()->info('gender: '.$request->input('gender'));
        // logger()->info(intval(Str::contains(strtolower($category->name), 'relay')));

        $data = [];
        $categoryTypeIds = $event->categoryTypes()
            ->where('master_match_category_id', $request->input('category'))
            ->pluck('master_match_type_id')
            ->toArray();
        // var_dump($categoryTypeIds, $request->input('category')); die;
        $checkboxes = $event->types()
            ->whereIn('id', $categoryTypeIds)
            ->when($request->input('gender') == 'male', function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'LIKE', '%PA')
                        ->orWhere('name', 'LIKE', '%PUTRA');
                });
            })
            ->when($request->input('gender') == 'female', function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'LIKE', '%PI')
                        ->orWhere('name', 'LIKE', '%PUTRI');
                });
            })
            ->when($request->input('gender') == 'mix', function ($q) use ($category) {
                if (Str::contains(strtolower($category->name), 'relay')) {
                    $q->where(function ($q) {
                        // $q->where('name', 'LIKE', '%PA')
                        //     ->orWhere('name', 'LIKE', '%PUTRA')
                        //     ->orWhere('name', 'LIKE', '%PI')
                        //     ->orWhere('name', 'LIKE', '%PUTRI')
                        //     ->orWhere('name', 'LIKE', '%MIX');
                        $q->where('name', 'LIKE', '%MIX');
                    });
                } else {
                    $q->where(function ($q) {
                        $q->where('name', 'NOT LIKE', '%PA')
                            ->where('name', 'NOT LIKE', '%PUTRA')
                            ->where('name', 'NOT LIKE', '%PI')
                            ->where('name', 'NOT LIKE', '%PUTRI')
                            ->where('name', 'NOT LIKE', '%MIX');
                        // $q->where('name', 'NOT LIKE', '%MIX');
                    });
                }
            })
            ->orderBy('ordering', 'asc')
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');

        if (! $checkboxes->count()) {
            return 'Tidak ditemukan gaya.';
        }

        $currentStyles = null;
        $additionalValues = null;
        // get participant event type values
        $masterSchool = MasterSchool::where('name', $request->school)->first();
        // logger()->info('$masterSchool->name');
        if (! empty($masterSchool)) {
            // logger()->debug($masterSchool->name);

            $masterParticipant = $this->getMasterParticipant($event, $masterSchool, $request);

            // logger()->info('$masterParticipant->name');
            if (! empty($masterParticipant)) {
                // logger()->debug($masterParticipant->name);

                // logger()->info('$checkboxes->keys()->all()');
                // logger()->debug($checkboxes->keys()->all());

                $currentStyles = $masterParticipant->styles()->whereIn('master_match_type_id', $checkboxes->keys()->all())->get();

                // logger()->info('$additionalValues');
                if ($currentStyles->isNotEmpty()) {
                    foreach ($currentStyles as $currentStyle) {
                        $additionalValues[$currentStyle->id] = $currentStyle->pivot->point_text;
                    }
                    // logger()->debug($additionalValues);
                }

                // get another style point
                $eventStageTable = (new EventStage)->getTable();
                $eventSessionTable = (new EventSession)->getTable();
                $eventSessionParticipantTable = (new EventSessionParticipant)->getTable();
                $eventSessionParticipants = EventSessionParticipant::select($eventSessionParticipantTable.'.*', $eventStageTable.'.master_match_type_id')
                    ->join($eventSessionTable, $eventSessionTable.'.id', '=', $eventSessionParticipantTable.'.event_session_id')
                    ->join($eventStageTable, $eventStageTable.'.id', '=', $eventSessionTable.'.event_stage_id')
                    ->whereIn($eventStageTable.'.master_match_type_id', $checkboxes->keys()->all())
                    ->where($eventSessionParticipantTable.'.master_participant_id', $masterParticipant->id)
                    ->where($eventSessionParticipantTable.'.disqualification', false)
                    ->get();

                // logger()->info('additional $additionalValues');
                if ($eventSessionParticipants->isNotEmpty()) {
                    foreach ($eventSessionParticipants as $eventSessionParticipant) {
                        if (empty($additionalValues[$eventSessionParticipant->master_match_type_id])) {
                            $additionalValues[$eventSessionParticipant->master_match_type_id] = $eventSessionParticipant->point_text;
                        }

                        if (! empty($additionalValues[$eventSessionParticipant->master_match_type_id])
                            && parsePointToInt($eventSessionParticipant->point_text) < parsePointToInt($additionalValues[$eventSessionParticipant->master_match_type_id])
                        ) {
                            $additionalValues[$eventSessionParticipant->master_match_type_id] = $eventSessionParticipant->point_text;
                        }
                    }
                    // logger()->debug($additionalValues);
                }
            }
        }

        $data['checkboxes'] = $checkboxes;
        $data['values'] = [];
        $data['name'] = 'style[]';
        $data['additionalName'] = 'style_value[]';
        // $data['additionalValues'] = $currentStyles;
        $data['additionalValues'] = $additionalValues;
        $data['separator'] = 'block';

        return view('front.competition._checkboxes-form', $data)->render();
    }

    private function getMasterParticipants(Event $event, MasterSchool $masterSchool)
    {
        $participants = MasterParticipant::where('master_school_id', '=', $masterSchool->id)
            ->where(function ($q) {
                $q->whereNotNull('gender')
                    ->orWhereNotNull('birth_year');
            })
            ->where(function ($q) {
                $q->whereHas('eventRegistrations')
                    ->orWhereHas('eventSessionParticipants');
            })
            ->when(! $event->is_has_mix_gender, function ($q) {
                $q->where('gender', '<>', 'mix');
            })
            ->whereNotNull('gender')
            ->whereNotNull('birth_year')
            ->orderBy('name')
            ->get();

        return $participants;
    }

    private function getMasterParticipant(Event $event, MasterSchool $masterSchool, Request $request)
    {
        // ## 1. get participant STRICT
        $masterParticipant = MasterParticipant::where('master_school_id', '=', $masterSchool->id)
            ->where('name', $request->name)
            ->when(! $event->is_has_mix_gender, function ($q) {
                $q->where('gender', '<>', 'mix');
            })
            ->where(function ($q) {
                $q->whereNotNull('gender')
                    ->whereNotNull('birth_year');
            })
            ->where(function ($q) {
                $q->whereHas('eventRegistrations')
                    ->whereHas('eventSessionParticipants');
            })
            ->first();
        // ## 2. get participant where gender or birth year can null
        if (empty($masterParticipant)) {
            $masterParticipant = MasterParticipant::where('master_school_id', '=', $masterSchool->id)
                ->where('name', $request->name)
                ->when(! $event->is_has_mix_gender, function ($q) {
                    $q->where('gender', '<>', 'mix');
                })
                ->where(function ($q) {
                    $q->whereNotNull('gender')
                        ->orWhereNotNull('birth_year');
                })
                ->where(function ($q) {
                    $q->whereHas('eventRegistrations')
                        ->whereHas('eventSessionParticipants');
                })
                ->first();
        }
        // ## 3. get participant where gender or birth year can null AND has been registered or an event participant
        if (empty($masterParticipant)) {
            $masterParticipant = MasterParticipant::where('master_school_id', '=', $masterSchool->id)
                ->where('name', $request->name)
                ->when(! $event->is_has_mix_gender, function ($q) {
                    $q->where('gender', '<>', 'mix');
                })
                ->where(function ($q) {
                    $q->whereNotNull('gender')
                        ->orWhereNotNull('birth_year');
                })
                ->where(function ($q) {
                    $q->whereHas('eventRegistrations')
                        ->orWhereHas('eventSessionParticipants');
                })
                ->first();
        }
        // ## n. get participant where only match name n school
        if (empty($masterParticipant)) {
            $masterParticipant = MasterParticipant::where('master_school_id', '=', $masterSchool->id)
                ->where('name', $request->name)
                ->when(! $event->is_has_mix_gender, function ($q) {
                    $q->where('gender', '<>', 'mix');
                })
                ->first();
        }

        return $masterParticipant;
    }

    // public function registerSearch(string $eventSlug, EventRegistrationSearchRequest $request)
    // {
    //     // dd($request->all());

    //     $event = Event::whereSlug($eventSlug)->firstOrFail();

    //     $eventRegistrations = $event->eventRegistrations()
    //         ->where('coach_name', $request->input('search_coach_name'))
    //         ->where('coach_phone', cleanPhoneNumber($request->input('search_coach_phone')))
    //         ->orderBy('id', 'asc')
    //         ->get();

    //     if (empty($eventRegistrations)) {
    //         return back()
    //             ->withInput()
    //             ->withErrors(['Maaf, Registrasi Kompetisi TIDAK ditemukan!']);
    //     }

    //     $minutes = 120;
    //     Cookie::queue('registration_coach_name', $request->input('search_coach_name'), $minutes);
    //     Cookie::queue('registration_coach_phone', $request->input('search_coach_phone'), $minutes);

    //     return back()
    //         ->withSuccess('Registrasi Kompetisi ditemukan!');
    // }
}
