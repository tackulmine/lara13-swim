{{-- {{ Form::bs4HorText('name', $type->name, ['class' => 'form-control toUppercase']) }} --}}
<x-forms.bs4.horizontal.text name="name" :value="$type->name" :input-attributes="['class' => 'form-control toUppercase']" :label="__('Gaya')" />
