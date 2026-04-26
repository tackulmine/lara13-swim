<table>
  <tbody>
    <tr>
      <td><strong>{{ strtoupper($event->name) }}</strong></td>
    </tr>
    <tr>
      <td><strong>{!! parseBetweenDate($event->start_date, $event->end_date) !!}</strong></td>
    </tr>
    <tr>
      <td><strong>ACARA {{ $eventStage->number_format }}.
          {{ strtoupper($eventStage->masterMatchType->name) }}</strong></td>
    </tr>
    <tr>
      <td><strong>KATEGORI {{ $eventStage->masterMatchCategory->name }}</strong></td>
    </tr>
  </tbody>
</table>
<table>
  <thead>
    <tr>
      <th><strong>{{ __('Pos') }}</strong></th>
      <th><strong>{{ __('Nama Lengkap Atlet') }}</strong></th>
      <th><strong>{{ __('Lahir') }}</strong></th>
      <th><strong>{{ __('Sekolah') }}</strong></th>
      {{-- <th><strong>ACARA</strong></th>
            <th><strong>TIPE GAYA</strong></th>
            <th><strong>KATEGORI</strong></th> --}}
      <th><strong>{{ __('Seri') }}</strong></th>
      <th><strong>{{ __('Lint') }}</strong></th>
      <th><strong>{{ __('Prestasi') }}</strong></th>
      <th><strong>{{ __('Final') }}</strong></th>
      <th><strong>{{ __('Ket') }}</strong></th>
    </tr>
  </thead>
  <tbody>
    @php
      $rank = 1;
    @endphp
    @foreach ($participants as $index => $participant)
      @php
        $pointBefore = null;
        if (!empty($participants[$index - 1])) {
            $pointBefore = !empty($participants[$index - 1]->point_decimal)
                ? $participants[$index - 1]->point_decimal
                : $participants[$index - 1]->point;
        }
        if (!empty($pointBefore) && $pointBefore !== $participant->point_decimal) {
            $rank++;
        }
      @endphp
      <tr>
        <td class="text-right">{!! $participant->disqualification ? '' : $rank !!}</td>
        <td>{{ strtoupper(optional($participant->masterParticipant)->name) }}</td>
        <td class="text-center">{{ optional($participant->masterParticipant)->birth_year }}</td>
        <td>{{ $participant->masterParticipant->masterSchool->name ?? '-' }}</td>
        {{-- <td class="text-center">{!! $participant->eventSession->eventStage->number_format !!}</td>
                <td>{{ $participant->eventSession->eventStage->masterMatchType->name }}</td>
                <td>{{ $participant->eventSession->eventStage->masterMatchCategory->name }}</td> --}}
        <td class="text-center">{!! $participant->eventSession->session !!}</td>
        <td class="text-center">{!! $participant->track !!}</td>
        <td class="text-center">
          {{ optional(optional($participant->masterParticipant->styles->where('id', $eventStage->masterMatchType->id)->first())->pivot)->point_text ?? 'NT' }}
        </td>
        <td class="text-right">{!! $participant->disqualification
            ? ($participant->dis_level == 2 || $participant->dis_level == 3
                ? // ? '<del>' . $participant->point_text . '</del>'
                '<del>99:99.99</del>'
                : $participant->point_text)
            : $participant->point_text !!}</td>
        <td class="text-center">{!! $participant->disqualification ? '<strong>' . $participant->dis_level_text . '</strong>' : '' !!}</td>
      </tr>
    @endforeach
  </tbody>
</table>
