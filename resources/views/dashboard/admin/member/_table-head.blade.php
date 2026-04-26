<tr>
  <th data-orderable="false"><input type="checkbox" name="select_all" value="1" id="select-all"></th>
  <th width="5%" data-orderable="false">No</th>
  <th>N a m a</th>
  <th class="text-right">Usia (th)</th>
  <th data-orderable="false">L/P</th>
  {{-- <th>Kelas</th> --}}
  <th class="text-right">No. Induk</th>
  {{-- <th data-orderable="false">{{ __('Atlet') }}?</th> --}}
  <th data-orderable="false">Telp/WA / Email</th>
  <th data-orderable="false">Photo</th>
  {{-- <th data-orderable="false">School</th> --}}
  <th width="10%">Dibuat</th>
  {{-- <th width="10%">Diupdate</th> --}}
  @if (auth()->user()->isCoach())
    <th data-orderable="false">Aktif?</th>
  @endif
  {{-- <th>Lahir/Umur</th> --}}
  <th width="5%" data-orderable="false">{{ __('Action') }}</th>
</tr>
