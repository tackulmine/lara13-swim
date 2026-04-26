<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#tim">Ranking Tim</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#atletTerbaikPerCat">Atlet Terbaik Per {{ __('Kategori') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#atletPerCat">Ranking Atlet Per {{ __('Kategori') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#atlet">Ranking Atlet</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#emas">Emas</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#perak">Perak</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#perunggu">Perunggu</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane pt-3 active" id="tim" role="tabpanel">
    <div class="table-responsive">
      @include($baseViewPath . '_participant-medal-table-tim', ['medalTim' => $medalTim])
    </div>
  </div>
  <div class="tab-pane pt-3" id="atletTerbaikPerCat" role="tabpanel">
    <div class="table-responsive">
      @include($baseViewPath . '_participant-medal-table-best-atlet-per-category-per-gender', [
          'bestAthletesPerCategoryPerGender' => $bestAthletesPerCategoryPerGender,
      ])
    </div>
  </div>
  <div class="tab-pane pt-3" id="atletPerCat" role="tabpanel">
    <div class="table-responsive">
      @include($baseViewPath . '_participant-medal-table-atlet-per-category-per-gender', [
          'medalPartPerCatsPerGender' => $medalPartPerCatsPerGender,
      ])
    </div>
  </div>
  <div class="tab-pane pt-3" id="atlet" role="tabpanel">
    <div class="table-responsive">
      {{-- @include($baseViewPath . '_participant-medal-table-atlet', ['participants' => $medalParticipants]) --}}
      @include($baseViewPath . '_participant-medal-table-atlet-per-gender', [
          'medalParticipantsPerGender' => $medalParticipantsPerGender,
      ])
    </div>
  </div>
  <div class="tab-pane pt-3" id="emas" role="tabpanel">
    <div class="table-responsive">
      {{-- @include($baseViewPath . '_participant-medal-table-item', [
          'participants' => $goldParticipants,
          'medal' => 'gold',
      ]) --}}
      @include($baseViewPath . '_participant-medal-table-item-per-gender', [
          'medalParticipantsPerGender' => $goldParticipantsPerGender,
          'medal' => 'gold',
      ])
    </div>
  </div>
  <div class="tab-pane pt-3" id="perak" role="tabpanel">
    <div class="table-responsive">
      {{-- @include($baseViewPath . '_participant-medal-table-item', [
          'participants' => $silverParticipants,
          'medal' => 'silver',
      ]) --}}
      @include($baseViewPath . '_participant-medal-table-item-per-gender', [
          'medalParticipantsPerGender' => $silverParticipantsPerGender,
          'medal' => 'silver',
      ])
    </div>
  </div>
  <div class="tab-pane pt-3" id="perunggu" role="tabpanel">
    <div class="table-responsive">
      {{-- @include($baseViewPath . '_participant-medal-table-item', [
          'participants' => $bronzeParticipants,
          'medal' => 'bronze',
      ]) --}}
      @include($baseViewPath . '_participant-medal-table-item-per-gender', [
          'medalParticipantsPerGender' => $bronzeParticipantsPerGender,
          'medal' => 'bronze',
      ])
    </div>
  </div>
</div>
