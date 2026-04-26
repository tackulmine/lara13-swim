<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Report;

use App\Models\ChampionshipEvent;
use App\Models\MasterChampionshipGaya;
use App\Models\User;
use App\Models\UserChampionship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class IndexController extends BaseController
{
    public function __invoke(Request $request)
    {
        $this->generateOptions($request);

        $data = [
            'pageTitle' => "{$this->moduleName}",
        ];

        $memberPath = 'members/'.$request->user_id.'/';

        if (
            $request->has('user_id')
            || $request->has('master_championship_gaya_id')
            || $request->has('periode_start')
            || $request->has('periode_end')
        ) {
            if (! auth()->user()->isSuperuser() && auth()->user()->isMember() && $request->user_id != auth()->id()) {
                return redirect()->route('dashboard.admin.kejuaraan.report.index', $request->merge(['user_id' => auth()->id()])->all());
            }

            if ($request->ajax()) {
                if (
                    $request->filled('user_id')
                    && $request->filled('master_championship_gaya_id')
                    && $request->filled('periode_start')
                    && $request->filled('periode_end')
                    && $request->filled('chart_image_data')
                ) {
                    $chartPathFile = $memberPath.'championship_'.$request->user_id.'-'.$request->master_championship_gaya_id.'-'.$request->periode_start.'-'.$request->periode_end.'.png';

                    // if (Storage::exists($chartPathFile)) {
                    //     return 'Exists!';
                    // }

                    // Get the base64 image data from the request
                    $base64Image = $request->input('chart_image_data');

                    // Remove the data URI scheme prefix (e.g., "data:image/png;base64,")
                    $image = str_replace('data:image/png;base64,', '', $base64Image);
                    $image = str_replace(' ', '+', $image);

                    // Decode the base64 string into binary data
                    $imageData = base64_decode($image);

                    // Save the image to the disk
                    Storage::put($chartPathFile, $imageData);

                    return 'Success';
                }

                return '';
            }

            $userChampionshipTable = (new UserChampionship)->getTable();
            $championshipEventTable = (new ChampionshipEvent)->getTable();
            $masterChampionshipGayaTable = (new MasterChampionshipGaya)->getTable();
            $userChampionships = UserChampionship::with('user', 'championshipEvent', 'championshipEvent.masterChampionship', 'masterChampionshipGaya')
                ->join($championshipEventTable, $championshipEventTable.'.id', '=', $userChampionshipTable.'.championship_event_id')
                ->join($masterChampionshipGayaTable, $masterChampionshipGayaTable.'.id', '=', $userChampionshipTable.'.master_championship_gaya_id')
                ->whereHas('user', function ($query) use ($request) {
                    $query->select('id');
                    $query->when($request->filled('user_id'), function ($query) use ($request) {
                        $query->where('user_id', (int) $request->user_id);
                    });
                })
                ->whereHas('masterChampionshipGaya', function ($query) use ($request) {
                    $query->select('id');
                    $query->when($request->filled('master_championship_gaya_id'), function ($query) use ($request) {
                        $query->where('master_championship_gaya_id', (int) $request->master_championship_gaya_id);
                    });
                })
                ->whereHas('championshipEvent.masterChampionship', function ($query) {
                    $query->select('id');
                })
                ->when(
                    $request->filled('periode_start') && $request->filled('periode_end'),
                    function ($query) use ($request, $championshipEventTable) {
                        $periodeStart = explode('-', $request->periode_start);
                        $periodeEnd = explode('-', $request->periode_end);

                        $query->where(function ($query) use ($periodeStart, $championshipEventTable) {
                            $query->whereMonth($championshipEventTable.'.start_date', '>=', (int) $periodeStart[0])
                                ->whereYear($championshipEventTable.'.start_date', '>=', (int) $periodeStart[1]);
                        })->where(function ($query) use ($periodeEnd, $championshipEventTable) {
                            $query->whereMonth($championshipEventTable.'.start_date', '<=', (int) $periodeEnd[0])
                                ->whereYear($championshipEventTable.'.start_date', '<=', (int) $periodeEnd[1]);
                        });
                    }
                )
                ->orderByRaw('CAST('.$masterChampionshipGayaTable.'.name AS UNSIGNED) ASC')
                ->oldest($championshipEventTable.'.start_date')
                ->selectRaw($userChampionshipTable.'.*, '.$championshipEventTable.'.master_championship_id')
                ->get();

            $data['userChampionships'] = $userChampionships;

            // dd($userChampionships->all());
            // dd($userChampionships->groupBy('master_championship_id'));

            $data['user'] = $user = User::whereHas('userChampionships')
                ->with(
                    'userMember',
                    'profile',
                    'educations',
                    'educations.school',
                )
                ->find($request->user_id);

            $data['gaya'] = $gaya = MasterChampionshipGaya::find($request->master_championship_gaya_id);

            $periodeStart = explode('-', request()->periode_start);
            $periodeEnd = explode('-', request()->periode_end);
            $periode = parseBetweenDateCustom(date($periodeStart[1].'-'.$periodeStart[0].'-01'), date($periodeEnd[1].'-'.$periodeEnd[0].'-01'));
            $periode = str_replace('&ndash;', '-', $periode);

            $pageTitles = [$this->moduleName, $user->name];
            if ($gaya) {
                $pageTitles[] = $periode;
            }
            $pageTitles[] = $periode;
            $data['pageTitle'] = implode(' - ', $pageTitles);
        }

        $this->globalData = $data + $this->globalData;

        if ($request->filled('print')) {
            foreach ($userChampionships->groupBy('master_championship_gaya_id') as $masterGayaId => $userChampionshipGroups) {
                // $baseFileName = 'championship_' . implode('-', $request->only('user_id', 'master_championship_gaya_id', 'periode_start', 'periode_end'));
                $fileNames = [$request->user_id, $masterGayaId, $request->periode_start, $request->periode_end];
                $baseFileName = 'championship_'.implode('-', $fileNames);
                $chartFileName = $baseFileName.'.png';

                $this->globalData['images'][$masterGayaId] = Storage::disk('shared')->path($memberPath.$chartFileName);

                // dd($this->globalData['images']);
            }

            if ($request->filled('view_only')) {
                return view($this->baseViewPath.'exports.championship.pdf-championship-report', $this->globalData);
            }

            // dompdf init
            $pdf = app('dompdf.wrapper');
            $pdf->getDomPDF()->set_option('enable_php', true);
            $pdf->loadView($this->baseViewPath.'exports.championship.pdf-championship-report', $this->globalData)
                ->setPaper('folio', 'portrait')
                ->setWarnings(false)
                ->setOption([
                    'dpi' => 150,
                    'defaultFont' => 'Bahnschrift',
                ]);

            $fileNames = [$request->user_id, $request->master_championship_gaya_id ?? null, $request->periode_start, $request->periode_end];
            $baseFileName = 'championship_'.implode('_', array_filter($fileNames));
            $pdfFileName = $baseFileName.'.pdf';
            if (app()->environment('production')) {
                return $pdf->download($pdfFileName);
            }

            return $pdf->stream($pdfFileName);
        }

        return view($this->baseViewPath.'index', $this->globalData);
    }
}
