<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class ExternalAthleteBestTimeExport implements FromView
{
    protected $styles;

    public function __construct(Collection $styles)
    {
        $this->styles = $styles;
    }

    /**
     * @return Collection
     */
    public function view(): View
    {
        return view('dashboard.admin.external.rank.exports.best-time', [
            'styles' => $this->styles,
        ]);
    }
}
