{{-- Periode & Gaya --}}
<div class="card mb-4">
  <div class="card-header py-3">
    <h6 class="m-0"><em>Periode dan {{ __('Gaya') }}</em></h6>
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
    {{ Form::bs4HorSelect(
        'master_class',
        $masterClassOptions,
        old('master_class'),
        [
            'class' => 'form-control',
        ],
        'Kelas',
    ) }}
  </div>
</div>

{{-- {{ __('Atlet') }} --}}
<div class="card mb-4">
  <div class="card-header py-3">
    <h6 class="m-0"><em>{{ __('Atlet') }}</em></h6>
  </div>
  <div class="card-body">
    <table class="table table-sm" id="batch-table">
      <thead>
        <tr>
          <th width="10%">No</th>
          <th>N a m a</th>
          <th>Kelas</th>
          <th width="40%">P o i n</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($members as $member)
          <tr class="{{ optional(optional($member->userMember)->class)->slug }}">
            <td>{{ $loop->iteration }}</td>
            {{-- <td>{{ $member->name }} ({{ optional(optional(optional($member->educations)->first())->school)->name ?? '-' }})</td> --}}
            <td><a href="javascript:;" data-toggle="tooltip"
                title="{{ $member->name }}">{{ $member->username ?? '-' }}</a></td>
            <td>{{ optional(optional($member->userMember)->class)->name }}</td>
            <td><input class="form-control text-right input-mask-time" data-inputmask-alias="datetime"
                data-inputmask-inputformat="MM:ss.L" data-inputmask-placeholder="00:00.00" placeholder="00:00.00"
                name="point_text[{!! $member->id !!}]" value="{{ old('point_text[{!! $member->id !!}]') }}"
                type="text"></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@push('js')
  <script>
    var selectedClass = $("[name=master_class]").val();
    if (selectedClass !== undefined && selectedClass !== "") {
      $("#batch-table tbody tr").show().filter(":not(." + selectedClass + ")").hide();
    }

    $("[name=master_class]").on("select2:select", function(e) {
      var data = e.params.data;
      // console.log(data);
      $("#batch-table tbody tr").show().filter(":not(." + data.id + ")").hide();
    });
  </script>
@endpush
