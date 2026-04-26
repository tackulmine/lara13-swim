<div role="tabpanel"class="row">
  <div class="col-xl-2 col-sm-4">
    <!-- List group -->
    <div class="list-group" id="myList" role="tablist">
      {{-- <a class="list-group-item list-group-item-action active" data-toggle="list" href="#home" role="tab">Home</a> --}}
      @if (!empty($eventCategories) && !$eventCategories->isEmpty())
        @foreach ($eventCategories as $eventCategory)
          <a class="list-group-item list-group-item-action @if ($loop->iteration == 1) active @endif"
            data-toggle="list" href="#{{ $eventCategory->slug }}" role="tab">{{ $eventCategory->name }}</a>
        @endforeach
      @else
        @foreach ($medalPartPerCats as $category => $participants)
          <a class="list-group-item list-group-item-action @if ($loop->iteration == 1) active @endif"
            data-toggle="list" href="#{{ $category }}"
            role="tab">{{ $participants->first()['data']->category_name }}</a>
        @endforeach
      @endif
    </div>
  </div>
  <div class="col-xl-10 col-sm-8">
    <!-- Tab panes -->
    <div class="tab-content">
      {{-- <div class="tab-pane active" id="home" role="tabpanel">...</div> --}}
      @if (!empty($eventCategories) && !$eventCategories->isEmpty())
        @foreach ($eventCategories as $eventCategory)
          @php
            $participants = $medalPartPerCats[$eventCategory->slug];
          @endphp
          <div class="tab-pane @if ($loop->iteration == 1) active @endif" id="{{ $eventCategory->slug }}"
            role="tabpanel">
            <table class="table table-light table-striped table-sm">
              <thead>
                <tr>
                  <th width="5%" class="text-center">No</th>
                  <th>Atlet</th>
                  <th width="5%" class="text-center">G</th>
                  <th width="25%">{{ __('Sekolah') }}</th>
                  <th width="12%" class="text-right">Total Emas</th>
                  <th width="12%" class="text-right">Total Perak</th>
                  <th width="12%" class="text-right">Total Perunggu</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $peringkat = 0;
                @endphp
                @foreach ($participants as $key => $participant)
                  @php
                    $totalMedal = $participant['gold'] . $participant['silver'] . $participant['bronze'];
                    if ($loop->iteration == 1) {
                        $peringkat = 1;
                        $currMedalTotal = $totalMedal;
                    }
                    // if ($peringkat < 4) {
                    if ($currMedalTotal != $totalMedal) {
                        $peringkat++;
                        $currMedalTotal = $totalMedal;
                    }
                    // }
                    $trClassStyle = in_array($peringkat, [1, 2, 3]) ? 'style="font-weight:bolder;"' : '';
                    $tdClassStyle =
                        $peringkat == 1
                            ? 'class="text-center text-success" style="font-size: 130%;"'
                            : ($peringkat == 2
                                ? 'class="text-center text-info" style="font-size: 120%;"'
                                : ($peringkat == 3
                                    ? 'class="text-center text-warning" style="font-size: 110%;"'
                                    : ''));
                  @endphp
                  <tr {!! $trClassStyle !!}>
                    <td {!! $tdClassStyle ?: 'class="text-center"' !!}>{{ $peringkat }}</td>
                    <td>{{ $participant['data']['participant_name'] }}</td>
                    <td class="text-center">
                      {{ parseGenderAbbr($participant['data']['participant_gender']) }}
                    </td>
                    <td>{{ $participant['data']['school_name'] }}</td>
                    <td class="text-right">{{ $participant['gold'] }}</td>
                    <td class="text-right">{{ $participant['silver'] }}</td>
                    <td class="text-right">{{ $participant['bronze'] }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endforeach
      @else
        @foreach ($medalPartPerCats as $category => $participants)
          <div class="tab-pane @if ($loop->iteration == 1) active @endif" id="{{ $category }}"
            role="tabpanel">
            <table class="table table-light table-striped table-sm">
              <thead>
                <tr>
                  <th width="5%" class="text-center">No</th>
                  <th>Atlet</th>
                  <th width="5%" class="text-center">G</th>
                  <th width="25%">{{ __('Sekolah') }}</th>
                  <th width="12%" class="text-right">Emas</th>
                  <th width="12%" class="text-right">Perak</th>
                  <th width="12%" class="text-right">Perunggu</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $peringkat = 0;
                @endphp
                @foreach ($participants as $key => $participant)
                  @php
                    $totalMedal = $participant['gold'] . $participant['silver'] . $participant['bronze'];
                    if ($loop->iteration == 1) {
                        $peringkat = 1;
                        $currMedalTotal = $totalMedal;
                    }
                    // if ($peringkat < 4) {
                    if ($currMedalTotal != $totalMedal) {
                        $peringkat++;
                        $currMedalTotal = $totalMedal;
                    }
                    // }
                    $trClassStyle = in_array($peringkat, [1, 2, 3]) ? 'style="font-weight:bolder;"' : '';
                    $tdClassStyle =
                        $peringkat == 1
                            ? 'class="text-center text-success" style="font-size: 130%;"'
                            : ($peringkat == 2
                                ? 'class="text-center text-info" style="font-size: 120%;"'
                                : ($peringkat == 3
                                    ? 'class="text-center text-warning" style="font-size: 110%;"'
                                    : ''));
                  @endphp
                  <tr {!! $trClassStyle !!}>
                    <td {!! $tdClassStyle ?: 'class="text-center"' !!}>{{ $peringkat }}</td>
                    <td>{{ $participant['data']['participant_name'] }}</td>
                    <td class="text-center">
                      {{ parseGenderAbbr($participant['data']['participant_gender']) }}
                    </td>
                    <td>{{ $participant['data']['school_name'] }}</td>
                    <td class="text-right">{{ $participant['gold'] }}</td>
                    <td class="text-right">{{ $participant['silver'] }}</td>
                    <td class="text-right">{{ $participant['bronze'] }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
