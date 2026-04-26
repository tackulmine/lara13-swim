{{-- Confirm Discard --}}
<div class="modal fade" id="modal-discard" tabIndex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Silakan Konfirmasi</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p class="lead">
          <i class="fa fa-question-circle fa-lg"></i> Batal membuat {{ $moduleName ?? 'item' }} baru?
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
        <a href="{{ route($baseRouteName . 'index') }}" class="btn btn-outline-warning">
          <i class="fa fa-undo"></i> Ya
        </a>
      </div>
    </div>
  </div>
</div>
