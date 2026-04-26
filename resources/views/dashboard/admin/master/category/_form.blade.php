{{-- {{ Form::bs4HorText('name', $category->name, ['class' => 'form-control toUppercase']) }} --}}
<x-forms.bs4.horizontal.text name="name" :value="$category->name" :input-attributes="['class' => 'form-control toUppercase']" :label="__('Nama Kategori')" />
