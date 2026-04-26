<?php

namespace App\DataTables\User;

use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MasterParticipantDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  mixed  $query  Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        $masterParticipantTable = (new MasterParticipant)->table();
        $masterSchoolTable = (new MasterSchool)->table();

        // return datatables()
        //     ->eloquent($query)
        //     ->addColumn('action', 'masterparticipant.action');
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($participant) {
                // return '
                //     <a href="' . route('dashboard.admin.master.participant.edit', $participant->id) . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                //     <a href="#!" data-url="' . route('dashboard.admin.master.participant.destroy', $participant->id) . '" class="btn btn-danger btn-sm btn-delete-participant"><i class="fa fa-trash"></i></a>
                // ';

                return '<a href="'.route('dashboard.admin.master.participant.edit', $participant->id).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
            })
            // ->editColumn('checkbox', function ($participant) {
            //     return '<input type="checkbox" name="ids[]" value="' . $participant->id . '">';
            // })
            ->editColumn('gender', function ($participant) {
                return $participant->gender_text;
            })
            ->editColumn('school', function ($participant) {
                return optional($participant->masterSchool)->name ?? '-';
            })
            ->filterColumn('school', function ($query, $keyword) use ($masterSchoolTable) {
                $query->where($masterSchoolTable.'.name', 'like', '%'.$keyword.'%');
            })
            ->orderColumn('school', function ($query, $order) use ($masterSchoolTable) {
                $query->orderBy($masterSchoolTable.'.name', $order ?? 'asc');
            })
            ->filterColumn('mendaftar', function ($query, $keyword) {
                $query->having('event_registrations_count', intval($keyword));
            })
            ->filterColumn('peserta', function ($query, $keyword) {
                $query->having('event_session_participants_count', intval($keyword));
            })
            ->filterColumn('gaya', function ($query, $keyword) {
                $query->having('styles_count', intval($keyword));
            })
            ->orderColumn('mendaftar', function ($query, $order) {
                $query->orderBy('event_registrations_count', $order ?? 'asc');
            })
            ->orderColumn('peserta', function ($query, $order) {
                $query->orderBy('event_session_participants_count', $order ?? 'asc');
            })
            ->orderColumn('gaya', function ($query, $order) {
                $query->orderBy('styles_count', $order ?? 'asc');
            })
            ->filterColumn('gender', function ($query, $keyword) use ($masterParticipantTable) {
                $qkeyword = $keyword;

                if (stristr('laki-laki', $keyword)) {
                    $qkeyword = 'male';
                }

                if (stristr('perempuan', $keyword)) {
                    $qkeyword = 'female';
                }

                if (stristr('mix', $keyword)) {
                    $qkeyword = 'mix';
                }

                if ($keyword == '-') {
                    $qkeyword = '';
                }

                if (! empty($qkeyword)) {
                    return $query->where($masterParticipantTable.'.gender', '=', $qkeyword);
                }

                return $query->whereNull($masterParticipantTable.'.gender');
            })
            // ->editColumn('created_at', function ($participant) {
            //     return Carbon::parse($participant->created_at)->format('d-m-Y');
            // })
            // ->editColumn('updated_at', function ($participant) {
            //     return Carbon::parse($participant->updated_at)->format('d-m-Y');
            // })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @return Builder
     */
    public function query(MasterParticipant $model)
    {
        $masterParticipantTable = (new MasterParticipant)->table();
        $masterSchoolTable = (new MasterSchool)->table();

        return $model->newQuery()
            ->leftJoin($masterSchoolTable, $masterSchoolTable.'.id', '=', $masterParticipantTable.'.master_school_id')
            ->with('masterSchool')
            ->withCount([
                'eventRegistrations',
                'eventSessionParticipants',
                'styles',
            ]);
        // ->orderBy('name', 'asc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('masterparticipant-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1, 'asc')
            ->buttons(
                // Button::make('create'),
                Button::make('pageLength'),
                Button::make('export'),
                Button::make('print'),
                Button::make('colvis'),
                Button::make('reset'),
                Button::make('reload')
            )
            ->addCheckbox(['class' => 'text-center no-search'], true)
            ->parameters([
                'responsive' => true,
                'paging' => true,
                // 'pagingType'   => "full_numbers",
                'searching' => true,
                'pageLength' => 10, // Set default page length
                'lengthMenu' => [
                    [10, 25, 50, 100, -1],
                    ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all'],
                ],
                'stateSave' => true,
                'searchDelay' => 500,
                // 'stateSaveCallback' => 'function (settings, data) {
                //     localStorage.setItem(
                //         "DataTables_" + settings.sInstance,
                //         JSON.stringify(data)
                //     );
                // }',
                // 'stateLoadCallback' => 'function (settings) {
                //     // console.log("settings.sInstance:");
                //     // console.log(settings.sInstance);
                //     // console.log("parsing:");
                //     // console.log(JSON.parse(localStorage.getItem("DataTables_" + settings.sInstance)));
                //     return JSON.parse(localStorage.getItem("DataTables_" + settings.sInstance));
                // }',
                'initComplete' => 'function() {
                    const table = this.api();

                    const $thead = $(table.table().header());

                    const $filterRow = $thead.find("tr").clone().addClass("filter");

                    $filterRow.find("th").each(function() {
                        const $currentTh = $(this);

                        if (!$currentTh.hasClass("no-search")) {

                            const input = $(`<input type="search" class="form-control form-control-sm" placeholder="${$currentTh.text()}" /> `);
                            $currentTh.html(input);

                            $(input).on("click", function(event) {
                                event.stopPropagation();
                            });

                            let count = 0;
                            $(input).on("keyup change clear search", function() {
                                if (table.column($currentTh.index()).search() !== this.value) {
                                    count++;
                                    const intervalId = setInterval(() => {
                                        // console.log(`Count: ${count}`);
                                        if (count > 0) {
                                            // console.log("This message appears after 1 seconds.");
                                            table.column($currentTh.index()).search(this.value).draw();

                                            // clearInterval(intervalId); // Stop the interval
                                            count = 0;
                                        }
                                        clearInterval(intervalId); // Stop the interval
                                    }, 500); // 500 milliseconds = .5 second
                                }
                            });

                        } else {
                            $currentTh.empty();
                        }

                    });

                    $thead.append($filterRow);
                }',
                'drawCallback' => 'function(settings){
                    const table = this.api();

                    const pageInfo = table.page.info();

                    $("#totalpages").text(pageInfo.pages);

                    let html = "";

                    let start = 0;

                    let length = pageInfo.length;

                    for(var count = 1; count <= pageInfo.pages; count++) {
                        let pageNumber = count - 1;

                        html += \'<option value="\'+pageNumber+\'" data-start="\'+start+\'" data-length="\'+length+\'">\'+count+\'</option>\';

                        start = start + pageInfo.length;
                    }

                    $("#pagelist").html(html);

                    $("#pagelist").val(pageInfo.page);

                    // function to go to a specific page
                    function goToPage(pageNumber) {
                        table.page(pageNumber).draw(false);
                    }

                    // jump to a page using a button
                    $("#pagelist").on("change", function() {
                        var pageNumber = $(this).val();
                        goToPage(parseInt(pageNumber));
                    });
                }',
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            // Column::computed('action')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->width(60)
            //     ->addClass('text-center'),
            // Column::make('id')
            //     ->title('ID')
            //     ->searchable(false)
            //     ->orderable(false)
            //     ->addClass('text-center no-search'),
            Column::make('name')->title(__('Nama Lengkap Atlet/ Tim Estafet')),
            Column::make('gender')->title(__('Gender')),
            Column::make('birth_year')->title(__('Lahir')),
            Column::make('school')
                ->title(__('Sekolah')),
            Column::make('mendaftar')->data('event_registrations_count')
                ->orderable(true)
                ->searchable(false)
                ->addClass('text-right no-search'),
            Column::make('peserta')->data('event_session_participants_count')
                ->orderable(true)
                ->searchable(false)
                ->addClass('text-right no-search'),
            Column::make('gaya')->data('styles_count')
                ->title(__('Gaya'))
                ->orderable(true)
                ->searchable(false)
                ->addClass('text-right no-search'),
            // Column::make('created_at'),
            // Column::make('updated_at'),
            Column::computed('action')
                ->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center no-search'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'MasterParticipant_'.date('YmdHis');
    }
}
