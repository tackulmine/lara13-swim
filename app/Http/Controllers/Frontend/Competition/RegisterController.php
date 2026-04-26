<?php

namespace App\Http\Controllers\Frontend\Competition;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\MasterMatchCategory;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class RegisterController extends BaseController
{
    public function __invoke(Request $request, string $eventSlug)
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

        // team/schools options setting
        $schoolOptions = MasterSchool::whereHas('masterParticipants', function ($q) {
            $q->where(function ($q2) {
                $q2->whereNotNull('gender')
                    ->orWhereNotNull('birth_year');
            })
                ->where(function ($q2) {
                    $q2->whereHas('eventRegistrations')
                        ->orWhereHas('eventSessionParticipants');
                });
        })
            ->orderBy('name')
            ->pluck('name', 'name')
            ->prepend('---', '');

        if ($event->eventSpecialTypes->isNotEmpty()) {
            $specialTypes = $event->eventSpecialTypes->pluck('name', 'name');

            $schoolOptions = $schoolOptions->merge($specialTypes);
        }

        $data['schoolOptions'] = $schoolOptions;

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
        if (old('name') && ! in_array(old('name'), $participantOptions)) {
            $participantOptions[old('name')] = old('name');
        }
        // dd($participantOptions);
        $data['participantOptions'] = $participantOptions;

        $registrationSchool = Cookie::get('registration_school');
        $registrationCoachName = Cookie::get('registration_coach_name');
        $registrationCoachPhone = Cookie::get('registration_coach_phone');

        // ### Get data from cookie if exists
        if (
            ! empty($registrationSchool)
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
        if (
            $request->filled('search_school')
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
            if (
                empty($registrationSchool)
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
            if (
                empty($registrationSchool)
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
}
