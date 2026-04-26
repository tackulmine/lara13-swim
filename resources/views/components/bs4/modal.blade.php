<div class="modal fade{{ !empty($modalClass) ? ' ' . $modalClass : '' }}"{{ !empty($modalId) ? ' id=' . $modalId : '' }}
  tabindex="-1" role="dialog">
  <div class="modal-dialog{{ !empty($modalSize) ? ' ' . $modalSize : '' }}" role="document">
    @if (!empty($modalFormUrl))
      {{-- {!! Form::open(array_merge(['url' => $modalFormUrl], $modalFormAttributes)) !!} --}}
      {{ html()->form('POST', $modalFormUrl)->attributes($modalFormAttributes)->open() }}
    @endif
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{!! !empty($modalTitle) ? ' ' . $modalTitle : '' !!}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{ $slot }}
      </div>
      @if (!empty($modalFooter) or !empty($modalFormUrl))
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
          @if (!empty($modalFormUrl))
            <button type="submit"
              class="btn btn-sm btn-primary{{ !empty($modalFormSubmitClass) ? ' ' . $modalFormSubmitClass : '' }}">{{ $modalFormSubmitText ?? 'Submit' }}</button>
          @endif
        </div>
      @endif
    </div>
    @if (!empty($modalFormUrl))
      {{ html()->form()->close() }}
    @endif
  </div>
</div>
