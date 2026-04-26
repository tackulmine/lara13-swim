@if ($userChampionships->isNotEmpty())
  <div class="mb-4">
    <canvas class="myChart" id="myChart-{{ $masterGayaId }}" style="width:100%;height:500px"></canvas>
  </div>
@endif
