{{-- {{ Form::bs4HorText('name', $event->name, ['required' => 'true'], "N a m a") }} --}}
<x-forms.bs4.horizontal.text name="name" :value="$event->name" :input-attributes="['required' => 'true']" label="N a m a" />
{{-- {{ Form::bs4HorText('address', $event->address, [], 'Alamat') }} --}}
<x-forms.bs4.horizontal.text name="address" :value="$event->address" :input-attributes="['required' => 'true']" label="Alamat" />
{{-- {{ Form::bs4HorText('location', $event->location, ['required' => 'true'], 'Lokasi') }} --}}
<x-forms.bs4.horizontal.text name="location" :value="$event->location" :input-attributes="['required' => 'true']" label="Lokasi" />
{{-- {{ Form::bs4HorText('date', (!empty($event->start_date) and !empty($event->end_date)) ? $event->start_date->format('d/m/Y H:i') . ' - ' . $event->end_date->format('d/m/Y H:i') : '', ['required' => 'true', 'class' => 'form-control daterange'], 'Tanggal') }} --}}
<x-forms.bs4.horizontal.text name="date" :value="(!empty($event->start_date) and !empty($event->end_date))
    ? $event->start_date->format('d/m/Y H:i') . ' - ' . $event->end_date->format('d/m/Y H:i')
    : ''" :input-attributes="['required' => 'true', 'class' => 'form-control daterange']" label="Tanggal" />
