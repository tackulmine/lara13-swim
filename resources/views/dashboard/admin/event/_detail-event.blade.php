<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 pt-1 font-weight-bold text-primary float-left">Detil Kompetisi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Total Acara</th>
                        <th>Total Seri</th>
                        <th>Total Peserta</th>
                        <th class="text-center">Selesai?</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->address }}</td>
                        <td>{{ $event->location }}</td>
                        <td><span
                                title="{{ $event->start_date->format('d/m/Y') }}">{{ $event->start_date->diffForHumans() }}</span>
                        </td>
                        <td class="text-right">
                            {{-- {{ $event->eventStages->count() }} --}}
                            <a href="{{ route($parentRouteName . 'event.stage.index', $event->id) }}">
                                {!! $event->eventStages->count() !!}
                            </a>
                        </td>
                        <td class="text-right">{!! $event->eventSessions->count() !!}</td>
                        <td class="text-right">
                            <a href="{{ route($parentRouteName . 'event.participant.index', $event->id) }}">
                                {!! $event->eventSessions->map(function ($q) {
                                        return $q->eventSessionParticipants->count();
                                    })->sum() !!}
                            </a>
                        </td>
                        <td class="text-center">{{ $event->completed ? 'Sudah' : 'Belum' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
