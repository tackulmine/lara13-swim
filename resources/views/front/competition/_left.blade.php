@include('layouts.partials._notif')
@php
  // dd($event->completed, $event->eventStages->count());
@endphp
@if ($event->completed || empty($event->eventStages->count()))
@else
  {{-- NO MORE STAGE --}}
  @if ($isAllStagesCompleted)
    @auth
      {!! Form::open(['url' => '/competition/' . $event->slug . '/done', 'method' => 'put']) !!}
      <p class="text-center">Klik tombol di bawah untuk mengakhiri kompetisi!</p>
      <p class="text-center my-2"><button class="btn btn-sm btn-warning" type="submit"
          onclick="return confirm('YAKIN MENGAKHIRI KOMPETISI ??? \n\nKlik \'Cancel\' jika ragu untuk cek ulang!')">Akhiri
          kompetisi <i class="fa fa-arrow-right"></i></button></p>
      {{ html()->form()->close() }}
    @endauth
  @else
    {{-- NO MORE SESSION --}}
    @if ($eventSession->completed)
      @auth
        {!! Form::open(['url' => '/competition/' . $event->slug . '/complete', 'method' => 'put']) !!}
        {!! Form::hidden('event_stage', $eventStage->id) !!}
        <p class="text-center">Klik tombol di bawah untuk melanjutkan acara lain!</p>
        <p class="text-center my-2"><button class="btn btn-sm btn-primary" type="submit"
            onclick="return confirm('YAKIN MELANJUTKAN ACARA ?? \n\nKlik \'Cancel\' jika ragu untuk cek ulang!')">Acara
            selanjutnya <i class="fa fa-arrow-right"></i></button></p>
        {{ html()->form()->close() }}
      @endauth
      @guest
        <p class="text-center">Menunggu acara selanjutnya!</p>
      @endguest
    @else
      @auth
        @push('js')
          <script>
            $('#input-excel-form-toggle').on('click', function(e) {
              $(this).parent().addClass('d-none');
              $('#input-excel-form').removeClass('d-none');
            });
          </script>
        @endpush
        <p class="text-right">
          <a href="javascript:;" class="text-white" id="input-excel-form-toggle">Input from excel?</a>
        </p>
        {!! Form::open([
            'url' => '/competition/' . $event->slug,
            'method' => 'post',
            'autocomplete' => 'off',
            'id' => 'input-excel-form',
            'class' => 'd-none',
        ]) !!}
        <div class="row mb-3">
          <div class="col-md-12 mb-2">
            {{-- {!! Form::label('Input from excel?') !!} --}}
            {!! Form::textarea('excel_values', old('excel_values', $excel_values ?? ''), [
                'class' => 'form-control',
                'placeholder' => '1  12:34.567  2',
                'required' => true,
            ]) !!}
          </div>
          <div class="col-md-12 text-right">
            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-magic"></i> Generate</button>
          </div>
        </div>
        {{ html()->form()->close() }}
      @endauth
      @auth
        {!! Form::open([
            'url' => '/competition/' . $event->slug . '/update',
            'method' => 'put',
            'autocomplete' => 'off',
            'id' => 'competition-form',
        ]) !!}
        {!! Form::hidden('event_stage', $eventStage->id) !!}
        {!! Form::hidden('event_session', $eventSession->id) !!}
      @endauth
      @php
        $j = 1;
      @endphp
      @for ($i = $minTrack; $i <= $maxTrack; $i++)
        @php
          switch ($j) {
              case 1:
                  $class = 'border-left-primary';
                  break;
              case 2:
                  $class = 'border-left-success';
                  break;
              case 3:
                  $class = 'border-left-info';
                  break;
              case 4:
                  $class = 'border-left-warning';
                  break;
              case 5:
                  $class = 'border-left-danger';
                  break;
              default:
                  $class = 'border-left-primary';
                  break;
          }
          $j++;
          if ($j > 5) {
              $j = 1;
          }
          $participant = $eventSessionParticipants->where('track', $i)->first() ?? null;

          // setup excel index
          if (!empty($excelValues) && count($excelValues) > 0) {
              $excelIndex = $i;
              // if ($minTrack > 0) {
              //     $excelIndex = $i - 1;
              // }
          }
          // dd($minTrack, $i, $excelIndex, $excelValues);
        @endphp
        <div class="card m-0 mb-2{{ !empty($class) ? ' ' . $class : '' }}">
          <div class="card-body p-2 bg-gray-700">
            <div class="row no-gutters">
              @if (!$participant)
                <div class="col-lg-8">
                  {{ $i }}. -
                </div>
                <div class="col-lg-4 text-right">
                  {{-- @auth
                    <input type="text" class="form-control form-control-sm text-right input-mask-time"
                      data-inputmask-alias="datetime" data-inputmask-inputformat="MM:ss.L"
                      data-inputmask-placeholder="00:00.00" placeholder="00:00.00"
                      disabled>
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" disabled>
                      <label class="custom-control-label my-0 pt-1">diskualifikasi?</label>
                    </div>
                  @endauth --}}
                </div>
              @else
                <div class="col-lg-8">
                  {{ $i }}.
                  <span
                    class="participant-name">{{ strtoupper(optional($participant->masterParticipant)->name) }}</span>
                  <br>&nbsp; &nbsp;
                  <small>({{ optional($participant->masterParticipant->masterSchool)->name ?? '-' }})</small>
                </div>
                <div class="col-lg-4 text-right">
                  @auth
                    <input type="text" class="form-control form-control-sm text-right input-mask-time"
                      data-inputmask-alias="datetime" data-inputmask-inputformat="MM:ss.l"
                      data-inputmask-placeholder="00:00.000" placeholder="00:00.000"
                      value="{{ old('participants.' . $participant->id . '.point') ??
                          (!empty($excelValues) && !empty($excelValues[$excelIndex]['waktu'])
                              ? $excelValues[$excelIndex]['waktu']
                              : $participant->point_text_decimal ?? $participant->point_text) }}"
                      name="participants[{!! $participant->id !!}][point]" class="participant-point">
                    {{-- <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input"
                        id="sesi-{{ $eventSession->session }}-{{ $i }}" value="1"
                        name="participants[{!! $participant->id !!}][dis]"
                        @if (old('participants.' . $participant->id . '.dis')) checked="checked" @endif>
                      <label class="custom-control-label my-0 pt-1"
                        for="sesi-{{ $eventSession->session }}-{{ $i }}">
                        diskualifikasi?</label>
                    </div> --}}
                  @endauth
                </div>
              @endif
            </div>

            @if (!empty($participant))
              @auth
                <div class="row no-gutters">
                  <div class="col-lg-12 text-right">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="sesi-{{ $eventSession->session }}-{{ $i }}-0" value="0"
                        name="participants[{!! $participant->id !!}][dis_level]" class="custom-control-input"
                        @if (old('participants.' . $participant->id . '.dis_level') == 0) checked="checked" @endif>
                      <label class="custom-control-label" for="sesi-{{ $eventSession->session }}-{{ $i }}-0"
                        title="No Dis">
                        -
                      </label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="sesi-{{ $eventSession->session }}-{{ $i }}-1" value="1"
                        name="participants[{!! $participant->id !!}][dis_level]" class="custom-control-input"
                        @if (old('participants.' . $participant->id . '.dis_level') == 1) checked="checked" @endif>
                      <label class="custom-control-label" for="sesi-{{ $eventSession->session }}-{{ $i }}-1"
                        title="Sparing Partner">
                        SP
                      </label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="sesi-{{ $eventSession->session }}-{{ $i }}-2" value="2"
                        name="participants[{!! $participant->id !!}][dis_level]" class="custom-control-input"
                        @if (old('participants.' . $participant->id . '.dis_level') == 2) checked="checked" @endif>
                      <label class="custom-control-label" for="sesi-{{ $eventSession->session }}-{{ $i }}-2"
                        title="Disqualification">
                        DQ
                      </label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline mr-0">
                      <input type="radio" id="sesi-{{ $eventSession->session }}-{{ $i }}-3" value="3"
                        name="participants[{!! $participant->id !!}][dis_level]" class="custom-control-input"
                        @if (old('participants.' . $participant->id . '.dis_level') == 3) checked="checked" @endif>
                      <label class="custom-control-label" for="sesi-{{ $eventSession->session }}-{{ $i }}-3"
                        title="Not Swim">
                        NS
                      </label>
                    </div>
                  </div>
                </div>
              @endauth
            @endif

          </div>
        </div>
      @endfor

      {{-- @foreach ($eventSessionParticipants as $participant)
        @php
          switch ($loop->iteration) {
              case 1:
                  $class = 'border-left-primary';
                  break;
              case 2:
                  $class = 'border-left-success';
                  break;
              case 3:
                  $class = 'border-left-info';
                  break;
              case 4:
                  $class = 'border-left-warning';
                  break;
              case 5:
                  $class = 'border-left-danger';
                  break;
              default:
                  $class = 'border-left-primary';
                  break;
          }
        @endphp
        <div class="card m-0 mb-2{{ !empty($class) ? ' ' . $class : '' }}">
          <div class="card-body p-2 bg-gray-700">
            <div class="row no-gutters">
              <div class="col-lg-8">
                {{ $participant->track }}. {{ $participant->masterParticipant->name }}
                <br>&nbsp; &nbsp;
                <small>({{ $participant->masterParticipant->masterSchool->name }})</small>
              </div>
              <div class="col-lg-4 text-right">
                @auth
                  <input type="text" class="form-control form-control-sm text-right input-mask-time"
                    data-inputmask-alias="datetime" data-inputmask-inputformat="MM:ss.L"
                    data-inputmask-placeholder="00:00.00" placeholder="00:00.00"
                    value="{{ old('participants.' . $participant->id . '.point') ?? $participant->point_text }}"
                    name="participants[{!! $participant->id !!}][point]">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input"
                      id="sesi-{{ $eventSession->session }}-{{ $loop->iteration }}" value="1"
                      name="participants[{!! $participant->id !!}][dis]"
                      @if (old('participants.' . $participant->id . '.dis')) checked="checked" @endif>
                    <label class="custom-control-label my-0 pt-1"
                      for="sesi-{{ $eventSession->session }}-{{ $loop->iteration }}">
                      diskualifikasi?</label>
                  </div>
                @endauth
              </div>
            </div>
          </div>
        </div>
      @endforeach --}}

      @auth
        <p class="text-right my-2"><button class="btn btn-sm btn-primary" type="submit"
            onclick="return confirm('YAKIN DATA TERSEBUT SUDAH BENAR ?? \n\nKlik \'Cancel\' jika ragu untuk cek ulang!')"><i
              class="fa fa-save"></i> Submit</button></p>
        {{ html()->form()->close() }}
      @endauth
    @endif
  @endif
@endif
