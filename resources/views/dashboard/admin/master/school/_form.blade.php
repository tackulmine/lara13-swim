{{-- {{ Form::bs4HorText('name', $school->name, ['class' => 'form-control toUppercase'], __('Sekolah')) }} --}}
<x-forms.bs4.horizontal.text name="name" :value="$school->name" :input-attributes="['class' => 'form-control toUppercase']" :label="__('Sekolah')" />
