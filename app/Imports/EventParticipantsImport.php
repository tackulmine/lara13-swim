<?php

namespace App\Imports;

use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use App\Models\MasterMatchCategory;
use App\Models\MasterMatchType;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EventParticipantsImport implements ToCollection, WithHeadingRow
{
    private $eventId;

    public function __construct(int $eventId)
    {
        $this->eventId = intval($eventId);
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $acara = cleanWhiteSpace($row['acara']);
            $tipe = cleanWhiteSpace($row['tipe']);
            $kategori = cleanWhiteSpace($row['kategori']);
            $nama = cleanWhiteSpace($row['nama']);
            $sekolah = cleanWhiteSpace($row['sekolah']);
            $seri = cleanWhiteSpace($row['seri']);
            $lintasan = cleanWhiteSpace($row['lintasan']);

            if (empty($acara)
                or empty($tipe)
                or empty($kategori)
                or empty($nama)
                // OR empty($sekolah)
                or empty($seri)
                or empty($lintasan)
            ) {
                continue;
            }

            $masterSchool = false;
            if (! empty($sekolah)) {
                $masterSchool = MasterSchool::firstOrCreate([
                    'name' => $sekolah,
                ]);
            }
            $masterMatchCategory = MasterMatchCategory::firstOrCreate([
                'name' => $kategori,
            ]);
            $masterMatchType = MasterMatchType::firstOrCreate([
                'name' => $tipe,
            ]);
            $gender = '';
            if (Str::contains(strtolower($tipe), ['-pa', 'putra'])) {
                $gender = 'male';
            }
            if (Str::contains(strtolower($tipe), ['-pi', 'putri'])) {
                $gender = 'female';
            }

            $acara = intval(str_replace("'", '', $acara));
            $eventStage = EventStage::firstOrCreate([
                'event_id' => $this->eventId,
                'master_match_type_id' => $masterMatchType->id,
                'master_match_category_id' => $masterMatchCategory->id,
                'number' => $acara,
                'completed' => 0,
            ]);

            $eventSession = EventSession::firstOrCreate([
                'event_stage_id' => $eventStage->id,
                'session' => $seri,
                'completed' => 0,
            ]);

            $masterParticipant = MasterParticipant::updateOrCreate([
                'master_school_id' => ! empty($masterSchool) ? $masterSchool->id : null,
                'name' => $nama,
            ], [
                'gender' => $gender,
            ]);

            if ($eventSession->id and $masterParticipant->id and ! empty($lintasan)) {
                EventSessionParticipant::updateOrCreate([
                    'event_session_id' => $eventSession->id,
                    'master_participant_id' => $masterParticipant->id,
                ], [
                    'track' => $lintasan,
                ]);
            }
        }
    }
}
