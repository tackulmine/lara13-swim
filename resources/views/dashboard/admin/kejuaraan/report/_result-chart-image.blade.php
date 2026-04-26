@if ($userChampionships->isNotEmpty())
  <div class="mb-4">
    @php
      $type = pathinfo($images[$masterGayaId], PATHINFO_EXTENSION);
      $imgData = file_get_contents($images[$masterGayaId]);
      $imgBase64 = 'data:image/' . $type . ';base64,' . base64_encode($imgData);
    @endphp
    <img src="{{ $imgBase64 }}" alt="-" width="100%">
  </div>
@endif
