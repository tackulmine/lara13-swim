<div class="card mb-4">
  <div class="card-header py-3">
    <h6 class="m-0"><em>{{ $memberGayaLimit->user_name }} ({{ $memberGayaLimit->master_school ?? '-' }})</em></h6>
  </div>
  <div class="card-body">
    {{ Form::bs4HorText(
        'periode_month_year',
        $memberGayaLimit->periode_month_year,
        [
            'data-provide' => 'datepicker',
            'data-date-format' => 'mm-yyyy',
            'data-date-view-mode' => 'months',
            'data-date-min-view-mode' => 'months',
            'data-date-end-date' => '0d',
            'data-date-autoClose' => 'true',
            'data-date-language' => 'id',
            'required' => 'required',
        ],
        'Bulan',
    ) }}

    {{ Form::bs4HorSelect(
        'master_gaya',
        $masterGayaOptions,
        $memberGayaLimit->master_gaya,
        [
            'class' => 'form-control',
            'required' => 'required',
            'data-tags' => 'true',
            'data-placeholder' => 'Pilih atau ketik baru',
            'data-allow-clear' => 'true',
        ],
        __('Gaya'),
    ) }}

    {{ Form::bs4HorText(
        'point_text',
        $memberGayaLimit->point_text,
        [
            'class' => 'form-control text-right input-mask-time',
            'data-inputmask-alias' => 'datetime',
            'data-inputmask-inputformat' => 'MM:ss.L',
            'data-inputmask-placeholder' => '00:00.00',
            'placeholder' => '00:00.00',
            'placeholder' => '00:00.00',
            'required' => 'required',
        ],
        'P o i n',
    ) }}
  </div>
</div>
