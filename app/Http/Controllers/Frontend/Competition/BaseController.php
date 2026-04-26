<?php

namespace App\Http\Controllers\Frontend\Competition;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function getMasterParticipants(Event $event, MasterSchool $masterSchool)
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

    protected function getMasterParticipant(Event $event, MasterSchool $masterSchool, Request $request)
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

    protected function parseString(string $str)
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
                $time = explode(':', $columns[1]);
                $hour = $time[0];
                $minute = $time[1];
                $milliSec = end($time);

                $parsed[] = [
                    'urutan' => $columns[0],
                    'waktu' => "{$hour}:{$minute}.{$milliSec}",
                    'lintasan' => $columns[2],
                ];
            }
        }

        return $parsed;
    }
}
