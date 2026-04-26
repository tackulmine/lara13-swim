<button type="button" class="btn btn-outline-secondary mb-2 mb-lg-0" data-toggle="modal" data-target="#modal-discard">
  <i class="fa fa-undo"></i>
  Batal
</button>
@if (empty($delete) || $delete != 'no')
  <button type="button" class="btn btn-outline-danger mb-2 mb-lg-0" data-toggle="modal" data-target="#modal-delete">
    <i class="fa fa-trash"></i>
    Hapus
  </button>
@endif
<div class="d-inline float-lg-right">
  <button type="submit" class="btn btn-outline-success mb-2 mb-lg-0" name="action" value="finished">
    <i class="fas fa-save"></i>
    Simpan dan kembali
  </button>
  <button type="submit" class="btn btn-outline-primary mb-2 mb-lg-0" name="action" value="continue">
    <i class="fas fa-save"></i>
    Simpan aja
  </button>
</div>
