<?php

namespace App\Libraries;

class FinaSeedingService
{
    // protected $laneOrder = [3, 4, 2, 5, 1, 6]; // Center → outward

    protected $laneOrder; // Center → outward

    public function generateHeats($peserta, $totalLane = 10, $startLane = 1)
    {
        $this->laneOrder = generateLaneOrder($totalLane);

        if ($startLane < 1) {
            $this->laneOrder = array_map(fn ($l) => $l - 1, $this->laneOrder);
        }

        // ==============================
        // 1. SORT SESUAI FINA
        // ==============================
        // ============================================================
        // 1B. PERBAIKAN Cek apakah semua achievement NULL → urutan jangan diganti
        // ============================================================
        $allNull = collect($peserta)->every(fn ($p) => is_null($p->achievement));

        if (! $allNull) {
            $pesertaAchievementSort = $peserta->filter(fn ($p) => ! is_null($p->achievement))
                ->sort(function ($a, $b) {
                    $aEmpty = empty($a->achievement);
                    $bEmpty = empty($b->achievement);

                    // // 1. NT dulu (achievement kosong)
                    // if ($aEmpty && ! $bEmpty) return -1;
                    // if (! $aEmpty && $bEmpty) return 1;

                    // // 2. Keduanya NT → sort by tahun lahir (termuda → tertua)
                    // if ($aEmpty && $bEmpty) {
                    //     return 0;
                    // }

                    // 3. Dua-duanya punya limit → sort by time ascending
                    // Convert 00:00.xx → float (misal 0029.56)
                    $aTime = floatval(str_replace(':', '', $a->achievement));
                    $bTime = floatval(str_replace(':', '', $b->achievement));

                    return $aTime <=> $bTime;
                });

            $pesertaEmptyAchievement = $peserta->filter(fn ($p) => is_null($p->achievement))
                ->sort(function ($a, $b) {
                    $aYear = $a->birth_year;
                    $bYear = $b->birth_year;

                    return $aYear <=> $bYear;
                });

            $peserta = $pesertaAchievementSort->merge($pesertaEmptyAchievement)->values();
        } else {
            $peserta = $peserta->sortBy('birth_year')->values();
        }

        // ==============================
        // 2. BAGI KE HEATS (seri)
        // ==============================
        $perSeri = $totalLane;
        $total = count($peserta);
        $jumlahSeri = ceil($total / $perSeri);

        $heats = [];
        $index = 0;

        for ($seri = 1; $seri <= $jumlahSeri; $seri++) {
            $heats[$seri] = $peserta->slice($index, $perSeri)->values();
            $index += $perSeri;
        }

        // ============================================================
        // 2B. PERBAIKAN HEAT YANG HANYA PUNYA 1 PESERTA (FINA RULE)
        // ============================================================
        $last = $jumlahSeri;
        $minLint = floor($perSeri / 2);
        $beforeLast = $jumlahSeri - 1;

        for ($i = 1; $i <= $minLint; $i++) {
            if ($jumlahSeri > 1 && isset($heats[$last]) && count($heats[$last]) < $minLint) {
                // Ambil 1 orang dari seri sebelumnya`
                $borrow = $heats[$beforeLast]->pop();

                // Masukkan ke seri terakhir
                $heats[$last]->push($borrow);
            }
        }

        // sorting kembali supaya seri terakhir ada di depan
        krsort($heats);
        // dump($heats);

        // ==============================
        // 3. PENEMPATAN LINTASAN
        // ==============================
        $index = 0;
        foreach ($heats as $seri => $list) {
            $index++;

            foreach ($list as $i => $p) {
                // $lane = $this->laneOrder[$i] ?? null;

                // Safe lane assignment (no undefined index)
                $lane = $this->laneOrder[$i % count($this->laneOrder)];

                $p->seri = $index;
                $p->lintasan = $lane;
            }
        }

        return $peserta;
    }
}
