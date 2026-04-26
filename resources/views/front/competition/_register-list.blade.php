<div class="table-responsive">
  <table class="table table-striped table-bordered" id="dataTableCustom" data-order="[[ 1, &quot;asc&quot; ]]"
    width="100%" cellspacing="0">
    <thead>
      @include('front.competition._register-list-thead')
    </thead>
    <tfoot>
      @include('front.competition._register-list-thead')
    </tfoot>
    <tbody>
      @php
        $i = 1;
      @endphp
      @forelse ($eventRegistrations as $eventRegistration)
        @foreach ($eventRegistration->types as $type)
          <tr>
            <td class="text-right">{{ $i++ }}</td>
            <td>{{ $eventRegistration->masterParticipant->name }}</td>
            <td class="text-center">{{ $eventRegistration->masterParticipant->birth_year }}</td>
            <td class="text-center">{{ $eventRegistration->masterParticipant->gender_text }}</td>
            <td>{{ $eventRegistration->masterParticipant->masterSchool->name }}</td>
            <td class="text-center">{{ $eventRegistration->masterMatchCategory->name }}</td>
            <td class="text-center">{{ $type->name }}</td>
            <td class="text-center" data-order="{!! empty($type->pivot->is_no_point) ? $type->pivot->point : 999999 !!}">
              {{ empty($type->pivot->is_no_point) ? $type->pivot->point_text : 'NT' }}
            </td>
            {{-- <td>{{ $eventRegistration->coach_name }}</td>
                        <td>{{ $eventRegistration->coach_phone }}</td>
                        <td class="text-center"><a href="{!! $eventRegistration->school_certificate_url !!}" target="_blank"><i class="fa fa-download"></i></a></td>
                        <td class="text-center"><a href="{!! $eventRegistration->birth_certificate_url !!}" target="_blank"><i class="fa fa-download"></i></a></td>
                        <td class="text-center">{!! $eventRegistration->preview_tiny_fancy_photo !!}</td> --}}
          </tr>
        @endforeach
      @empty
        <tr>
          <td colspan="100" align="center">Data kosong!</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
