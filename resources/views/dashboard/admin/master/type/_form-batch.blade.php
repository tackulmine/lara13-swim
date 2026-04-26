{{-- {{ Form::bs4HorTextarea('names', old('names'), ['class' => 'form-control toUppercase'], 'Nama type per baris') }} --}}
<x-forms.bs4.horizontal.textarea name="names" :value="old('names')" :input-attributes="['class' => 'form-control toUppercase']" :label="'Nama ' . __('Gaya') . ' per baris'" />
