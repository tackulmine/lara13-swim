<?php

namespace App\Imports;

use App\Models\ExternalAthleteBestTime;
use App\Models\ExternalSwimmingAthlete;
use App\Models\ExternalSwimmingClub;
use App\Models\ExternalSwimmingEvent;
use App\Models\ExternalSwimmingStyle;
use App\Models\MasterCity;
use App\Models\MasterProvince;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class FirstExternalAthleteBestTimesImport implements ToCollection
{
    private $sheet;

    public function __construct(int $sheet)
    {
        $this->sheet = intval($sheet);
    }

    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $rows)
    {
        // dd($rows->toArray());
        Log::info('=================== START SHEET ===================');
        Log::info('SHEET: '.$this->sheet);
        $newStyle = false;
        $newAthlete = false;
        $startAthlete = false;
        $style = false;
        foreach ($rows as $row => $cols) {
            // dump($cols);

            Log::info('=================== ROW ===================');
            Log::info('ROW: '.$row);

            if (empty($cols[1])) {
                Log::info('SKIPPING: '.$row);

                continue;
            }
            Log::info('PROCESSING: '.$row);

            // STYLE
            $styleCol = $cols[1];
            if (! empty($styleCol) and Str::contains($styleCol, ['MEN', 'WOMEN', 'LCM'])) {
                $newStyle = true;
            }

            if ($newStyle) {
                $styleName = cleanWhiteSpace($styleCol);
                $styleName = Str::before($styleName, ',');
                $styleName = trim(Str::after($styleName, ')'));

                if (empty($styleName)) {
                    throw new \Exception('Empty Style Name!');
                }

                $styleCode = Str::before($styleCol, ')');
                $styleCode = str_replace('(', '', $styleCode) ?? '';

                $style = ExternalSwimmingStyle::updateOrCreate([
                    'name' => $styleName,
                ], [
                    'code' => $styleCode,
                ]);

                $newStyle = false;
                $startAthlete = false;

                continue;
            }

            // ATHLETE
            $athleteCol = strtolower($cols[3]);
            if (! empty($athleteCol) and stristr($athleteCol, 'Athlete')) {
                $newAthlete = true;
                $startAthlete = true;

                continue;
            }

            if ($startAthlete && $style) {
                $rnk = cleanWhiteSpace($cols[1]);
                $nisnas = cleanWhiteSpace($cols[2]);
                $athleteName = cleanWhiteSpace($cols[3]);
                $dob = cleanWhiteSpace($cols[5]);
                $clubName = cleanWhiteSpace($cols[6]);
                $cityName = cleanWhiteSpace($cols[7]);
                $provinceName = cleanWhiteSpace($cols[8]);
                $eventName = cleanWhiteSpace($cols[9]);
                $bestTime = cleanWhiteSpace($cols[10]);
                $fp = cleanWhiteSpace($cols[11]);

                Log::info('provinceName: '.$provinceName);

                $cityName = str_replace(['.', 'KAB'], ['', 'KABUPATEN'], $cityName);
                Log::info('cityName: '.$cityName);

                $province = MasterProvince::where('name', $provinceName)->firstOrFail();
                $city = MasterCity::where('name', $cityName)
                    ->where('province_code', $province->code)
                    ->firstOrFail();

                if (empty($clubName)) {
                    throw new \Exception('Empty Club Name!');
                }

                $club = ExternalSwimmingClub::updateOrCreate([
                    'name' => $clubName,
                ], [
                    'city_code' => $city->code,
                ]);

                if (empty($athleteName)) {
                    throw new \Exception('Empty Athlete Name!');
                }

                $athlete = ExternalSwimmingAthlete::updateOrCreate([
                    'name' => $athleteName,
                ], [
                    'nisnas' => $nisnas,
                    'dob' => Carbon::createFromFormat('j/n/Y', $dob),
                    'gender' => Str::contains($styleCol, 'WOMEN') ? 'female' : (Str::contains($styleCol, 'MEN') ? 'male' : 'mix'),
                    'city_code' => $city->code,
                    'external_swimming_club_id' => $club->id,
                ]);

                if (empty($eventName)) {
                    throw new \Exception('Empty Event Name!');
                }

                $year = substr($eventName, -4);
                Log::info('Year: '.$year);

                $year = intval($year) > 0 ? $year : date('Y');

                $event = ExternalSwimmingEvent::updateOrCreate([
                    'name' => $eventName,
                ], [
                    'code' => $eventName,
                    'city_code' => $city->code,
                    'year' => $year,
                ]);

                ExternalAthleteBestTime::updateOrCreate([
                    'external_swimming_style_id' => $style->id,
                    'external_swimming_athlete_id' => $athlete->id,
                    'external_swimming_event_id' => $event->id,
                ], [
                    'year' => $year,
                    'point_text' => normalizePoint($bestTime),
                    'point' => parsePointToInt($bestTime),
                    'fp' => $fp,
                ]);
            }
        }
        Log::info('=================== END SHEET ===================');
        // dd('die');
    }
}
