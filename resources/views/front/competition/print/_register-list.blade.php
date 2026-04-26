<table class="table table-sm table-striped" width="100%" cellspacing="0">
  {{-- <thead> --}}
  @include('front.competition.print._register-list-thead')
  {{-- </thead> --}}
  {{-- <tbody> --}}
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
        <td class="text-center">{{ empty($type->pivot->is_no_point) ? $type->pivot->point_text : 'NT' }}</td>
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
  {{-- </tbody> --}}
</table>
