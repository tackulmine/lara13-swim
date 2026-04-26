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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class FirstEventParticipantSheetImport implements ToCollection, WithChunkReading
{
    private $eventId;

    private $sheet;

    public function __construct(int $eventId, int $sheet)
    {
        $this->eventId = intval($eventId);
        $this->sheet = intval($sheet);
    }

    public function collection(Collection $rows)
    {
        // dd($rows);
        // dd($rows->toArray());
        $newAcara = false;
        $startAcara = false;
        $newSeri = false;
        $startSeri = false;
        $newLint = false;
        $startLint = false;
        $gender = '';
        Log::info('=================== START SHEET ===================');
        Log::info('SHEET: '.$this->sheet);
        foreach ($rows as $row => $cols) {
            Log::info('=================== ROW ===================');
            Log::info('ROW: '.$row);
            // ACARA
            $acaraCol = strtolower($cols[0]);
            if (! empty($acaraCol) and stristr($acaraCol, 'acara')) {
                $newAcara = true;
            }

            if ($newAcara) {
                $acara = cleanWhiteSpace($acaraCol);
                $acara = str_replace(['acara 00', 'acara 0', 'acara ', 'acarav ', 'acara'], '', $acara);
                $tipe = cleanWhiteSpace($cols[1]);
                $kategori = cleanWhiteSpace($cols[5]);

                if (
                    empty($acara) or intval($acara) <= 0
                    or empty($tipe)
                    or empty($kategori)
                ) {
                    continue;
                }

                $masterMatchCategory = MasterMatchCategory::firstOrCreate([
                    'name' => strtoupper($kategori),
                ]);
                $masterMatchType = MasterMatchType::firstOrCreate([
                    'name' => strtoupper($tipe),
                ]);
                $gender = '';
                if (Str::contains(strtolower($tipe), ['-pa', 'putra'])) {
                    $gender = 'male';
                }
                if (Str::contains(strtolower($tipe), ['-pi', 'putri'])) {
                    $gender = 'female';
                }
                if (Str::contains(strtolower($tipe), ['-mix', 'mix'])) {
                    $gender = 'mix';
                }
                $eventStageCount = EventStage::whereEventId($this->eventId)->count();
                $eventStage = EventStage::firstOrCreate([
                    'event_id' => $this->eventId,
                    'master_match_type_id' => $masterMatchType->id,
                    'master_match_category_id' => $masterMatchCategory->id,
                    'number' => intval($acara),
                    'order_number' => $eventStageCount++,
                    'completed' => 0,
                ]);
                $newAcara = false;
                $startAcara = true;
            }

            // SERI
            $seriCol = strtolower($cols[0]);
            if (
                $startAcara
                and ! empty($seriCol)
                and stristr($seriCol, 'seri')
            ) {
                $newSeri = true;
            }

            if ($newSeri && ! empty($eventStage)) {
                $seri = cleanWhiteSpace($seriCol);
                $seri = str_replace(['seri ', 'seri'], '', $seri);
                if (empty($seri) or intval($seri) <= 0) {
                    continue;
                }

                $eventSession = EventSession::firstOrCreate([
                    'event_stage_id' => $eventStage->id,
                    'session' => intval($seri),
                    'completed' => 0,
                ]);
                $newSeri = false;
                $startSeri = true;
            }

            // LINT
            $lintCol = strtolower($cols[0]);
            if (
                $startSeri
                and ! empty($lintCol)
                // and (stristr($lintCol, 'lint') or intval($lintCol) > 0)
                and stristr($lintCol, 'lint')
            ) {
                $newLint = true;
            }

            if ($newLint) {
                $lint = cleanWhiteSpace($lintCol);
                // if (empty($lint) or intval($lint) <= 0) {
                if (
                    $lint == ''
                    or stristr($lint, 'lint')
                    or stristr($lint, 'acara')
                    or ! in_array($lint, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10])
                ) {
                    continue;
                }
                $newLint = false;
                $startLint = true;
            }

            if ($startLint and ! $newLint and ! empty($masterMatchType)) {
                $lintasan = cleanWhiteSpace($lintCol);
                $namaCol = str_replace(['.', ','], ' ', $cols[1]);
                // ## if value is contains SP will setted as Sparing Partner participant
                $nama = cleanWhiteSpace($namaCol);
                $lahir = cleanWhiteSpace($cols[2]);
                $lahir = str_replace(['ku ', 'ku'], '', strtolower($lahir));
                $sekolah = cleanWhiteSpace($cols[3]);
                $prestasiText = cleanWhiteSpace($cols[4]);
                $prestasi = ! empty($prestasiText) ? parsePointToInt($prestasiText) : null;
                // ## if value is contains SP will setted as Sparing Partner participant
                $keterangan = cleanWhiteSpace($cols[5]);

                // check if participant is SP
                $isSparingParticipant = false;
                if (
                    Str::contains(strtolower($nama), ['(sp)', 'sparing'])
                    || Str::contains(strtolower($prestasiText), ['sp', 'sparing'])
                    || Str::contains(strtolower($keterangan), ['sp', 'sparing'])
                ) {
                    $isSparingParticipant = true;
                }

                if ((! isset($lintasan) or intval($lintasan) < 0)
                    or stristr($lintasan, 'acara')
                    or empty($nama)
                    // or empty($sekolah)
                ) {
                    continue;
                }

                // dump($cols->toArray());
                Log::info('PRESTASI TEXT: '.$prestasi);

                $masterSchool = false;
                if (! empty($sekolah)) {
                    $masterSchool = MasterSchool::firstOrCreate([
                        'name' => $sekolah,
                    ]);
                }

                $masterParticipant = MasterParticipant::updateOrCreate([
                    'master_school_id' => ! empty($masterSchool) ? $masterSchool->id : null,
                    'name' => $nama,
                    'gender' => $gender,
                    'birth_year' => intval($lahir) > 0 ? intval($lahir) : null,
                ], [
                    'master_school_id' => ! empty($masterSchool) ? $masterSchool->id : null,
                    'name' => $nama,
                    'gender' => $gender,
                    'birth_year' => intval($lahir) > 0 ? intval($lahir) : null,
                ]);

                // update best limit atlet per gaya
                $prestasiSet = intval($prestasi) > 0
                    && (
                        $prestasi != '99.99.99'
                        || $prestasi != '99:99.99'
                        || $prestasi != '09.09.99'
                        || $prestasi != '09:09.99'
                    );
                $masterParticipant->styles()->syncWithoutDetaching([
                    $masterMatchType->id => [
                        'is_no_point' => ($prestasiSet ? 0 : 1),
                        'point' => ($prestasiSet ? $prestasi : null),
                        'point_text' => ($prestasiSet ? $prestasiText : null),
                    ],
                ]);

                if ($eventSession->id and $masterParticipant->id and isset($lintasan)) {
                    EventSessionParticipant::updateOrCreate([
                        'event_session_id' => $eventSession->id,
                        'master_participant_id' => $masterParticipant->id,
                        'disqualification' => (bool) $isSparingParticipant,
                        'dis_level' => $isSparingParticipant ? EventSessionParticipant::DIS_LEVEL_SP : null,
                    ], [
                        'track' => intval($lintasan),
                        'notes' => $keterangan,
                    ]);
                }
            }
        }
        Log::info('=================== END SHEET ===================');
    }

    public function chunkSize(): int
    {
        return 1000; // Adjust based on your server's capabilities
    }
}
