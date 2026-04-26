<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use App\Models\MasterParticipant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ViewMedalParticipantControllerOld extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request, Event $event)
    {
        // $sql = "SELECT esp1.id
        //         FROM `event_session_participants` esp1
        //         INNER JOIN `master_participants` mp1
        //             ON mp1.id = esp1.master_participant_id
        //         INNER JOIN event_sessions es1
        //             ON es1.id = esp1.event_session_id
        //         INNER JOIN event_stages est1
        //             ON est1.id = es1.event_stage_id
        //         INNER JOIN (
        //             SELECT MIN(sp.`point`) min_point
        //             FROM `event_session_participants` sp
        //             INNER JOIN `event_sessions` s
        //                 ON s.`id` = sp.`event_session_id`
        //             INNER JOIN `event_stages` st
        //                 ON st.`id` = s.`event_stage_id`
        //             WHERE st.`event_id` = 4
        //             AND sp.`disqualification` <> 1
        //             GROUP BY s.`event_stage_id`
        //         ) esp2
        //             ON esp1.`point` = esp2.min_point
        //         WHERE est1.`event_id` = 4
        //         AND esp1.`disqualification` <> 1
        //         GROUP BY es1.`event_stage_id`";
        // $sql2 = "SELECT esp.master_participant_id,
        //         mp.`name`,
        //         COUNT(esp.master_participant_id) AS total_medali
        //     FROM event_session_participants esp
        //     LEFT JOIN master_participants mp
        //         ON mp.id = esp.master_participant_id
        //     WHERE esp.id IN ({$sql})
        //     GROUP BY esp.master_participant_id
        //     ORDER BY total_medali DESC";

        $cacheTtl = config('cache.ttl');
        // GOLD
        $cacheKey = "|centrumsc|event|{$event->slug}|gold-participant-ids|";
        $goldParticipantIds = cache()->remember($cacheKey, $cacheTtl, function () use ($event) {
            $eventSessionParticipantTable = (new EventSessionParticipant)->getTable();
            $masterParticipantTable = (new MasterParticipant)->getTable();
            $eventSessionTable = (new EventSession)->getTable();
            $eventStageTable = (new EventStage)->getTable();

            return EventSessionParticipant::query()
                ->join($masterParticipantTable, $masterParticipantTable.'.id', '=', $eventSessionParticipantTable.'.master_participant_id')
                ->join($eventSessionTable, $eventSessionTable.'.id', '=', $eventSessionParticipantTable.'.event_session_id')
                ->join($eventStageTable, $eventStageTable.'.id', '=', $eventSessionTable.'.event_stage_id')
                ->join(
                    DB::raw("(SELECT MIN(sp.`point`) min_point
                        FROM `{$eventSessionParticipantTable}` sp
                        INNER JOIN `{$eventSessionTable}` s
                            ON s.`id` = sp.`event_session_id`
                        INNER JOIN `{$eventStageTable}` st
                            ON st.`id` = s.`event_stage_id`
                        WHERE st.`event_id` = {$event->id}
                        AND sp.`disqualification` <> 1
                        GROUP BY s.`event_stage_id`
                    ) esp2
                    "),
                    $eventSessionParticipantTable.'.point',
                    '=',
                    'esp2.min_point'
                )
                ->where($eventStageTable.'.event_id', $event->id)
                ->where($eventSessionParticipantTable.'.disqualification', '<>', 1)
                ->groupBy("{$eventSessionTable}.event_stage_id")
                ->pluck("{$eventSessionParticipantTable}.id");
            // dd($goldParticipantIds);
        });
        // dd($goldParticipantIds->implode(","));
        $goldParticipants = EventSessionParticipant::select('*')
            ->selectRaw('COUNT(master_participant_id) as total_medali')
            ->with([
                'masterParticipant:id,name,master_school_id',
                'masterParticipant.masterSchool:id,name',
            ])
            ->hasIn('masterParticipant')
            ->whereIn('id', $goldParticipantIds)
            ->groupBy('master_participant_id')
            ->orderByDesc('total_medali')
            ->get();
        // $arr = [];
        // foreach ($goldParticipants as $goldParticipant) {
        //     $arr[] = [
        //         'name' => $goldParticipant->masterParticipant->name,
        //         'tim' => $goldParticipant->masterParticipant->masterSchool->name,
        //         'total_medali' => $goldParticipant->total_medali,
        //     ];
        // }

        // SILVER
        $exceptionParticipantIdCommas = $goldParticipantIds->unique()->sort()->implode(',');
        $cacheKey = "|centrumsc|event|{$event->slug}|silver-participant-ids|";
        $silverParticipantIds = cache()->remember($cacheKey, $cacheTtl, function () use ($event, $exceptionParticipantIdCommas) {
            $eventSessionParticipantTable = (new EventSessionParticipant)->getTable();
            $masterParticipantTable = (new MasterParticipant)->getTable();
            $eventSessionTable = (new EventSession)->getTable();
            $eventStageTable = (new EventStage)->getTable();

            return EventSessionParticipant::query()
                ->join($masterParticipantTable, $masterParticipantTable.'.id', '=', $eventSessionParticipantTable.'.master_participant_id')
                ->join($eventSessionTable, $eventSessionTable.'.id', '=', $eventSessionParticipantTable.'.event_session_id')
                ->join($eventStageTable, $eventStageTable.'.id', '=', $eventSessionTable.'.event_stage_id')
                ->join(
                    DB::raw("(SELECT MIN(sp.`point`) min_point
                        FROM `{$eventSessionParticipantTable}` sp
                        INNER JOIN `{$eventSessionTable}` s
                            ON s.`id` = sp.`event_session_id`
                        INNER JOIN `{$eventStageTable}` st
                            ON st.`id` = s.`event_stage_id`
                        WHERE st.`event_id` = {$event->id}
                        AND sp.`disqualification` <> 1
                        AND sp.`id` NOT IN ({$exceptionParticipantIdCommas})
                        GROUP BY s.`event_stage_id`
                    ) esp2
                    "),
                    $eventSessionParticipantTable.'.point',
                    '=',
                    'esp2.min_point'
                )
                ->where($eventStageTable.'.event_id', $event->id)
                ->where($eventSessionParticipantTable.'.disqualification', '<>', 1)
                ->groupBy("{$eventSessionTable}.event_stage_id")
                ->pluck("{$eventSessionParticipantTable}.id");
            // dd($silverParticipantIds);
        });
        $silverParticipants = EventSessionParticipant::select('*')
            ->selectRaw('COUNT(master_participant_id) as total_medali')
            ->with([
                'masterParticipant:id,name,master_school_id',
                'masterParticipant.masterSchool:id,name',
            ])
            ->hasIn('masterParticipant')
            ->whereIn('id', $silverParticipantIds)
            ->groupBy('master_participant_id')
            ->orderByDesc('total_medali')
            ->get();

        // BRONZE
        $exceptionParticipantIdCommas = $goldParticipantIds->merge($silverParticipantIds)->unique()->sort()->implode(',');
        $cacheKey = "|centrumsc|event|{$event->slug}|bronze-participant-ids|";
        $bronzeParticipantIds = cache()->remember($cacheKey, $cacheTtl, function () use ($event, $exceptionParticipantIdCommas) {
            $eventSessionParticipantTable = (new EventSessionParticipant)->getTable();
            $masterParticipantTable = (new MasterParticipant)->getTable();
            $eventSessionTable = (new EventSession)->getTable();
            $eventStageTable = (new EventStage)->getTable();

            return EventSessionParticipant::query()
                ->join($masterParticipantTable, $masterParticipantTable.'.id', '=', $eventSessionParticipantTable.'.master_participant_id')
                ->join($eventSessionTable, $eventSessionTable.'.id', '=', $eventSessionParticipantTable.'.event_session_id')
                ->join($eventStageTable, $eventStageTable.'.id', '=', $eventSessionTable.'.event_stage_id')
                ->join(
                    DB::raw("(SELECT MIN(sp.`point`) min_point
                        FROM `{$eventSessionParticipantTable}` sp
                        INNER JOIN `{$eventSessionTable}` s
                            ON s.`id` = sp.`event_session_id`
                        INNER JOIN `{$eventStageTable}` st
                            ON st.`id` = s.`event_stage_id`
                        WHERE st.`event_id` = {$event->id}
                        AND sp.`disqualification` <> 1
                        AND sp.`id` NOT IN ({$exceptionParticipantIdCommas})
                        GROUP BY s.`event_stage_id`
                    ) esp2
                    "),
                    $eventSessionParticipantTable.'.point',
                    '=',
                    'esp2.min_point'
                )
                ->where($eventStageTable.'.event_id', $event->id)
                ->where($eventSessionParticipantTable.'.disqualification', '<>', 1)
                ->groupBy("{$eventSessionTable}.event_stage_id")
                ->pluck("{$eventSessionParticipantTable}.id");
            // dd($bronzeParticipantIds);
        });
        $bronzeParticipants = EventSessionParticipant::select('*')
            ->selectRaw('COUNT(master_participant_id) as total_medali')
            ->with([
                'masterParticipant:id,name,master_school_id',
                'masterParticipant.masterSchool:id,name',
            ])
            ->hasIn('masterParticipant')
            ->whereIn('id', $bronzeParticipantIds)
            ->groupBy('master_participant_id')
            ->orderByDesc('total_medali')
            ->get();

        $this->globalData = [
            'pageTitle' => "Medali Peserta {$this->moduleName} '{$event->name}'",
            'event' => $event,
            'goldParticipants' => $goldParticipants,
            'silverParticipants' => $silverParticipants,
            'bronzeParticipants' => $bronzeParticipants,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_participant-medal-table', $this->globalData)->render();
        }

        // dd($this->globalData);
        return view($this->baseViewPath.'view-participant-medal', $this->globalData);
    }
}
