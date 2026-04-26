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
          <i class="fa fa-question-circle fa-lg"></i> Batal mengubah {{ $moduleName ?? 'item' }} ini?
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
        <a href="{{ route($baseRouteName . 'index') . getQueryHttpBuilder() }}" class="btn btn-warning">
          <i class="fa fa-undo"></i> Ya
        </a>
      </div>
    </div>
  </div>
</div>

@if (empty($delete) || $delete != 'no')
  {{-- Confirm Delete --}}
  <div class="modal fade" id="modal-delete" tabIndex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Silakan Konfirmasi</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <p class="lead">
            <i class="fa fa-question-circle fa-lg"></i> Hapus {{ $moduleName ?? 'item' }} ini?
          </p>
        </div>
        <div class="modal-footer">
          <form action="{{ route($baseRouteName . 'destroy', $id) . getQueryHttpBuilder() }}" method="post">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-outline-danger">
              <i class="fa fa-trash"></i> Ya
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endif
