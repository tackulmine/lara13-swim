<tr>
  <td>
    {{ Form::select('user_id[]', $participantOptions, null, ['class' => 'form-control', 'required' => true, 'disabled' => true]) }}
  </td>
  <td>
    {{ Form::select('master_championship_gaya_id[]', $gayaOptions, null, ['class' => 'form-control', 'required' => true, 'disabled' => true]) }}
  </td>
  <td>
    {{ Form::text('point_text[]', null, [
        'class' => 'form-control text-right input-mask-time',
        'data-inputmask-alias' => 'datetime',
        'data-inputmask-inputformat' => 'MM:ss.L',
        'data-inputmask-placeholder' => '00:00.00',
        'placeholder' => '00:00.00',
        'style' => 'min-width: 100px',
        'required' => true,
        'disabled' => true,
    ]) }}
  </td>
  <td>{{ Form::text('rank[]', null, ['class' => 'form-control', 'style' => 'min-width: 100px', 'disabled' => true]) }}
  </td>
  <td class="text-right">
    <a href="javascript:;" class="btn btn-danger btn-sm btn-circle del-item" title="delete row">
      <i class="fa fa-trash"></i>
    </a>
    <a href="javascript:;" class="btn btn-primary btn-sm btn-circle add-item" title="add row">
      <i class="fa fa-plus"></i>
    </a>
  </td>
</tr>
