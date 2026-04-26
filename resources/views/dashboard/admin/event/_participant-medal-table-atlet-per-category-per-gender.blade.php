<div role="tabpanel" class="row">
  <div class="col-lg-2 col-sm-3">
    <div class="list-group" id="myList" role="tablist">
      @foreach ($medalPartPerCatsPerGender as $category => $genderGroups)
        <a class="list-group-item list-group-item-action @if ($loop->first) active @endif"
          data-toggle="list" href="#category-{{ $category }}" role="tab">
          {{ $genderGroups->first()->first()['data']->category_name }}
        </a>
      @endforeach
    </div>
  </div>
  <div class="col-lg-10 col-sm-9">
    <div class="tab-content">
      @foreach ($medalPartPerCatsPerGender as $category => $genderGroups)
        <div class="tab-pane @if ($loop->first) active @endif" id="category-{{ $category }}" role="tabpanel">
          @foreach ($genderGroups as $gender => $participants)
            <h5 style="color: #858796; border-bottom: 1px solid #858796;">Rekap Medali {{ parseGender($gender) }}</h5>
            <table class="table table-light table-striped table-sm">
              <thead>
                <tr>
                  <th width="5%" class="text-center">No</th>
                  <th>Atlet</th>
                  <th width="25%">{{ __('Sekolah') }}</th>
                  <th width="12%" class="text-right">Emas</th>
                  <th width="12%" class="text-right">Perak</th>
                  <th width="12%" class="text-right">Perunggu</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($participants as $participant)
                  @php
                    $rank = $participant['rank'];
                    $trClass = in_array($rank, [1, 2, 3]) ? 'font-weight-bold' : '';
                    $tdClass = $rank == 1 ? 'text-center text-success' : ($rank == 2 ? 'text-center text-info' : ($rank == 3 ? 'text-center text-warning' : 'text-center'));
                    $fontSize = $rank == 1 ? 'font-size: 130%;' : ($rank == 2 ? 'font-size: 120%;' : ($rank == 3 ? 'font-size: 110%;' : ''));
                  @endphp
                  <tr class="{{ $trClass }}">
                    <td class="{{ $tdClass }}" style="{{ $fontSize }}">{{ $rank }}</td>
                    <td>{{ $participant['data']->participant_name }}</td>
                    <td>{{ $participant['data']->school_name }}</td>
                    <td class="text-right">{{ $participant['gold'] }}</td>
                    <td class="text-right">{{ $participant['silver'] }}</td>
                    <td class="text-right">{{ $participant['bronze'] }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @endforeach
        </div>
      @endforeach
    </div>
  </div>
</div>
