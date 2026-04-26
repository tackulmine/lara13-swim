<?php

namespace App\DataTables\Admin;

use App\Models\MasterSchool;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MasterSchoolDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  mixed  $query  Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        $masterSchoolTable = (new MasterSchool)->table();

        return datatables()
            ->eloquent($query)
            // return (new EloquentDataTable($query))
            ->addColumn('action', function ($school) {
                // return '
                //     <a href="' . route('dashboard.admin.master.school.edit', $school->id) . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                //     <a href="#!" data-url="' . route('dashboard.admin.master.school.destroy', $school->id) . '" class="btn btn-danger btn-sm btn-delete-school"><i class="fa fa-trash"></i></a>
                // ';

                return '<a href="'.route('dashboard.admin.master.school.edit', $school->id).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
            })
            ->filterColumn('total_peserta', function ($query, $keyword) {
                $query->having('master_participants_count', intval($keyword));
            })
            ->filterColumn('total_member', function ($query, $keyword) {
                $query->having('user_educations_count', intval($keyword));
            })
            ->orderColumn('total_peserta', function ($query, $order) {
                $query->orderBy('master_participants_count', $order ?? 'asc');
            })
            ->orderColumn('total_member', function ($query, $order) {
                $query->orderBy('user_educations_count', $order ?? 'asc');
            })
            ->editColumn('created_at', function ($school) {
                return Carbon::parse($school->created_at)->format('d-m-Y');
            })
            // ->editColumn('updated_at', function ($school) {
            //     return Carbon::parse($school->updated_at)->format('d-m-Y');
            // })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @return Builder
     */
    public function query(MasterSchool $model)
    {
        $masterSchoolTable = (new MasterSchool)->table();

        return $model->newQuery()
            ->withCount([
                'masterParticipants',
                'userEducations',
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
            ->setTableId('masterschool-table')
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
     */
    protected function getColumns(): array
    {
        return [
            Column::make('name')->title(__('Sekolah')),
            Column::make('total_peserta')->data('master_participants_count')
                ->orderable(true)
                ->searchable(false)
                ->addClass('text-right no-search'),
            Column::make('total_member')->data('user_educations_count')
                ->orderable(true)
                ->searchable(false)
                ->addClass('text-right no-search'),
            Column::make('created_at'),
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
     */
    protected function filename(): string
    {
        return 'MasterSchool_'.date('YmdHis');
    }
}
