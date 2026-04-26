{{ Form::bs4HorText('name', $event->name, ['required' => 'true'], "N a m a") }}
{{ Form::bs4HorText('address', $event->address, [], "Alamat") }}
{{ Form::bs4HorText('location', $event->location, ['required' => 'true'], "Lokasi") }}
{{ Form::bs4HorText('date', (!empty($event->start_date) and !empty($event->end_date)) ? $event->start_date->format('d/m/Y H:i') . ' - ' . $event->end_date->format('d/m/Y H:i') : '', ['required' => 'true', 'class' => 'form-control daterange'], "Tanggal") }}
