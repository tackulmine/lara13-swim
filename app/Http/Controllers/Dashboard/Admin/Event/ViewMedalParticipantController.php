<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use App\Models\MasterMatchCategory;
use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ViewMedalParticipantController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request, Event $event)
    {
        $participantResults = $this->getParticipantResults($event);
        $medals = $this->categorizeMedalsByRank($participantResults);
        $medalCounts = $this->groupMedalsByParticipant($medals);
        $medalParticipants = $this->mergeMedalCounts($medalCounts);
        $medalTim = $this->groupMedalsBySchool($medalParticipants);
        $medalTimWithRank = $this->addRankToCollection($medalTim);
        $medalPartPerCats = $medalParticipants->groupBy('data.category_slug')->sortKeys();
        $medalPartPerCatsPerGender = $this->getMedalPerCategoryPerGender($medalPartPerCats);
        $bestAthletesPerCategoryPerGender = $this->getBestAthletesPerCategoryPerGender($medalPartPerCats);

        $this->globalData = [
            'pageTitle' => "Perolehan Medali {$this->moduleName} '{$event->name}'",
            'event' => $event,
            'goldParticipants' => $medalCounts['gold'],
            'silverParticipants' => $medalCounts['silver'],
            'bronzeParticipants' => $medalCounts['bronze'],
            'goldParticipantsPerGender' => $medalCounts['gold']->groupBy('data.participant_gender')->sortKeysDesc(),
            'silverParticipantsPerGender' => $medalCounts['silver']->groupBy('data.participant_gender')->sortKeysDesc(),
            'bronzeParticipantsPerGender' => $medalCounts['bronze']->groupBy('data.participant_gender')->sortKeysDesc(),
            'medalParticipants' => $medalParticipants,
            'medalParticipantsPerGender' => $medalParticipants->groupBy('data.participant_gender')->sortKeysDesc(),
            'medalPartPerCats' => $medalPartPerCats,
            'medalPartPerCatsPerGender' => $medalPartPerCatsPerGender,
            'medalTim' => $medalTimWithRank,
            'bestAthletesPerCategoryPerGender' => $bestAthletesPerCategoryPerGender,
        ] + $this->globalData;

        return request()->ajax()
            ? view($this->baseViewPath.'_participant-medal-table', $this->globalData)->render()
            : view($this->baseViewPath.'view-participant-medal', $this->globalData);
    }

    private function getParticipantResults(Event $event)
    {
        return EventSessionParticipant::select(
            's.event_stage_id',
            'sp.master_participant_id',
            'mp.name as participant_name',
            'mp.slug as participant_slug',
            'mp.gender as participant_gender',
            'ms.name as school_name',
            'ms.slug as school_slug',
            'c.name as category_name',
            'c.slug as category_slug'
        )
            ->selectRaw('sp.point, sp.point_decimal')
            ->from(DB::raw((new EventSessionParticipant)->getTable().' as sp'))
            ->join(DB::raw((new MasterParticipant)->getTable().' as mp'), 'mp.id', '=', 'sp.master_participant_id')
            ->join(DB::raw((new MasterSchool)->getTable().' as ms'), 'ms.id', '=', 'mp.master_school_id')
            ->join(DB::raw((new EventSession)->getTable().' as s'), 's.id', '=', 'sp.event_session_id')
            ->join(DB::raw((new EventStage)->getTable().' as st'), 'st.id', '=', 's.event_stage_id')
            ->join(DB::raw((new MasterMatchCategory)->getTable().' as c'), 'c.id', '=', 'st.master_match_category_id')
            ->where('st.completed', true)
            ->where('s.completed', true)
            ->where('st.event_id', $event->id)
            ->where('sp.disqualification', '<>', 1)
            ->orderBy('st.order_number')
            ->orderBy('sp.point_decimal')
            ->orderBy('sp.point')
            ->get();
    }

    private function categorizeMedalsByRank($participantResults)
    {
        $medals = ['gold' => collect([]), 'silver' => collect([]), 'bronze' => collect([])];

        foreach ($participantResults->groupBy('event_stage_id') as $participants) {
            $rank = 1;
            foreach ($participants as $index => $participant) {
                $participantBefore = $participants[$index - 1] ?? null;
                $pointBefore = $participantBefore ? ($participantBefore->point_decimal ?? $participantBefore->point) : null;
                $currentPoint = $participant->point_decimal ?? $participant->point;

                if ($pointBefore && $pointBefore !== $currentPoint) {
                    $rank++;
                }
                if ($rank > 3) {
                    break;
                }

                if ($rank == 1) {
                    $medals['gold']->push($participant);
                }
                if ($rank == 2) {
                    $medals['silver']->push($participant);
                }
                if ($rank == 3) {
                    $medals['bronze']->push($participant);
                }
            }
        }

        return $medals;
    }

    private function groupMedalsByParticipant($medals)
    {
        $grouped = [];

        foreach (['gold', 'silver', 'bronze'] as $type) {
            $grouped[$type] = $medals[$type]->groupBy('participant_slug')->map(function ($group) use ($type) {
                return [
                    'data' => $group->first(),
                    'gold' => $type === 'gold' ? $group->count() : 0,
                    'silver' => $type === 'silver' ? $group->count() : 0,
                    'bronze' => $type === 'bronze' ? $group->count() : 0,
                ];
            })->sortByDesc($type);
        }

        return $grouped;
    }

    private function mergeMedalCounts($medalCounts)
    {
        return collect([])
            ->merge($medalCounts['gold'])
            ->merge($medalCounts['silver'])
            ->merge($medalCounts['bronze'])
            ->map(function ($item, $key) use ($medalCounts) {
                return [
                    'data' => $item['data'],
                    'gold' => $medalCounts['gold'][$key]['gold'] ?? 0,
                    'silver' => $medalCounts['silver'][$key]['silver'] ?? 0,
                    'bronze' => $item['bronze'],
                ];
            })
            ->sortByDesc(fn ($item) => [$item['gold'], $item['silver'], $item['bronze']]);
    }

    private function groupMedalsBySchool($medalParticipants)
    {
        return $medalParticipants->groupBy('data.school_slug')->map(function ($item) {
            return [
                'data' => [
                    'school_name' => $item->first()['data']->school_name,
                    'school_slug' => $item->first()['data']->school_slug,
                ],
                'gold' => $item->sum('gold'),
                'silver' => $item->sum('silver'),
                'bronze' => $item->sum('bronze'),
            ];
        })->sortByDesc(fn ($item) => [$item['gold'], $item['silver'], $item['bronze']]);
    }

    private function getMedalPerCategoryPerGender($medalPartPerCats)
    {
        return $medalPartPerCats->map(function ($participants) {
            return $participants->groupBy('data.participant_gender')->map(function ($genderGroup) {
                $rank = 0;
                $prevMedals = null;

                return $genderGroup->sortByDesc(fn ($item) => [$item['gold'], $item['silver'], $item['bronze']])
                    ->values()
                    ->map(function ($item) use (&$rank, &$prevMedals) {
                        $currentMedals = [$item['gold'], $item['silver'], $item['bronze']];
                        if ($prevMedals !== $currentMedals) {
                            $rank++;
                            $prevMedals = $currentMedals;
                        }
                        $item['rank'] = $rank;

                        return $item;
                    });
            })->sortKeysDesc();
        });
    }

    private function addRankToCollection($collection)
    {
        $rank = 0;
        $prevMedals = null;

        return $collection->map(function ($item) use (&$rank, &$prevMedals) {
            $currentMedals = [$item['gold'], $item['silver'], $item['bronze']];
            if ($prevMedals !== $currentMedals) {
                $rank++;
                $prevMedals = $currentMedals;
            }
            $item['rank'] = $rank;

            return $item;
        });
    }

    private function getBestAthletesPerCategoryPerGender($medalPartPerCats)
    {
        return $medalPartPerCats->map(function ($participants) {
            return $participants->groupBy('data.participant_gender')->map(function ($genderGroup) {
                $sorted = $genderGroup->sortByDesc(fn ($item) => [$item['gold'], $item['silver'], $item['bronze']])->values();
                if ($sorted->isEmpty()) {
                    return collect([]);
                }

                $best = $sorted->first();

                return $sorted->filter(fn ($item) => $item['gold'] === $best['gold'] &&
                    $item['silver'] === $best['silver'] &&
                    $item['bronze'] === $best['bronze']
                )->values();
            });
        });
    }
}
