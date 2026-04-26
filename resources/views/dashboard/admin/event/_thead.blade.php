<tr>
  <th><input type="checkbox" name="select_all" value="1" id="select-all"></th>
  {{-- <th>No</th> --}}
  <th width="5%" data-orderable="false">No</th>
  <th>N a m a</th>
  {{-- <th>Alamat</th> --}}
  <th>Lokasi</th>
  <th>Logo</th>
  <th>Tanggal</th>
  <th data-orderable="false">Pendaftaran/ Akhir/ Kuota</th>
  @if (!auth()->user()->hasRole('external') || auth()->user()->isSuperuser())
    <th>Tipe?</th>
  @endif
  <th class="text-right">Total Acara</th>
  <th class="text-right">Total Seri</th>
  <th class="text-right">Total Peserta (Unik)</th>
  <th class="text-center">Status?</th>
  <th width="5%" data-orderable="false">{{ __('Action') }}</th>
</tr>
