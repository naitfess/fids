@extends('layouts.frontpage')

@section('content')
    <div class="container-fluid py-4">
        <div class="pt-3 pb-4 text-center">
            <div class="d-flex flex-column flex-md-row justify-content-center align-items-center mb-2 gap-3">
                <img class="logo-front img-fluid me-md-3 mb-2 mb-md-0" src="{{ asset('logo/logo_kemenhub.PNG') }}"
                    alt="Logo Kemenhub">
                <div>
                    <h1 class="mb-1 fw-bold text-uppercase fs-1 fs-md-2">Bandar Udara Muara Bungo</h1>
                </div>
                <img class="logo-front img-fluid ms-md-3 mt-2 mt-md-0" src="{{ asset('logo/logo_muarabungo.PNG') }}"
                    alt="Logo Muara Bungo">
            </div>
        </div>

        <div class="py-2 py-md-4">
            <div class="mx-0 mx-md-3">
                <div class="bg-white overflow-hidden shadow-sm rounded-3">
                    <div class="pt-4 pt-md-6 px-3 px-md-6 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <div class="fs-4 fs-md-3 fw-bold mb-2 mb-md-0">
                            @if ($type == 'arrival')
                                ARRIVAL
                            @else
                                DEPARTURE
                            @endif
                        </div>
                        <div class="d-flex flex-column text-md-end">
                            <div id="current-date" class="small small-md"></div>
                            <div id="current-time" class="fs-4 fs-md-3 fw-semibold"></div>
                        </div>
                    </div>

                    <div class="p-3 p-md-6 text-gray-900" id="arrival-flights-table">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="br-tl">Airline</th>
                                        <th scope="col">Flight Number</th>
                                        @if ($type == 'arrival')
                                            <th scope="col">Origin</th>
                                        @else
                                            <th scope="col">Destination</th>
                                        @endif
                                        <th scope="col">Scheduled</th>
                                        <th scope="col">Gate</th>
                                        <th scope="col">Status</th>
                                        {{-- <th scope="col" class="br-tr">Cuaca</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($flights->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-center border-0 py-3">
                                                No {{ $type == 'arrival' ? 'arrival' : 'departure' }} flights available.
                                            </td>
                                        </tr>
                                    @endif
                                    @foreach ($flights as $flight)
                                        <tr class="align-middle">
                                            <th scope="row">
                                                <div class="logo-sm d-flex align-items-center">
                                                    <img class="img-fluid" src="{{ asset('uploads/' . $flight->user->logo) }}" alt="">
                                                </div>
                                            </th>
                                            <td class="fw-semibold">{{ $flight->flight_number }}</td>
                                            @if ($type == 'arrival')
                                                <td>{{ @$flight->origin->name }}</td>
                                            @else
                                                <td>{{ @$flight->destination->name }}</td>
                                            @endif
                                            <td>
                                                {{ $flight->scheduled_time ? \Illuminate\Support\Carbon::parse($flight->scheduled_time)->format('H:i') : '' }}
                                            </td>
                                            <td>{{ $flight->gate }}</td>
                                            <td>
                                                {{ $flight->status }}
                                                {{ $flight->delayed_until ? ' Until ' . \Illuminate\Support\Carbon::parse($flight->delayed_until)->format('H:i') : '' }}
                                            </td>
                                            {{-- <td>
                                                @if ($type == 'arrival' ? $flight->origin->weatherReport : $flight->destination->weatherReport)
                                                    <div class="d-flex align-items-center">
                                                        <div style="width: 50px" class="flex-shrink-0">
                                                            <img class="img-fluid"
                                                                src="https://openweathermap.org/img/wn/{{ $type == 'arrival' ? $flight->origin->weatherReport->icon : $flight->destination->weatherReport->icon }}@2x.png"
                                                                alt="">
                                                        </div>
                                                        <div class="d-flex flex-column ms-2">
                                                            <div>
                                                                {{ $type == 'arrival' ? $flight->origin->weatherReport->temperature : $flight->destination->weatherReport->temperature }}°C
                                                            </div>
                                                            <div class="small">
                                                                {{ $type == 'arrival' ? $flight->origin->weatherReport->weather : $flight->destination->weatherReport->weather }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>-</div>
                                                @endif
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex justify-content-center justify-content-md-end">
                            {{ $flights->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                        let lastUpdate = new Date().toISOString();
            function updateDateTime() {
                const now = new Date();

                const dateFormatter = new Intl.DateTimeFormat('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });

                const dateEl = document.getElementById('current-date');
                const timeEl = document.getElementById('current-time');

                if (dateEl) dateEl.textContent = dateFormatter.format(now);

                if (timeEl) {
                    const h = String(now.getHours()).padStart(2, '0');
                    const m = String(now.getMinutes()).padStart(2, '0');
                    const s = String(now.getSeconds()).padStart(2, '0');
                    timeEl.textContent = `${h}:${m}:${s}`;
                }
            }

            function checkForUpdates() {
                fetch('/api/flights-updates')
                    .then(response => response.json())
                    .then(data => {
                        if (new Date(data.last_update) > new Date(lastUpdate)) {
                            lastUpdate = data.last_update;
                            location.reload();
                        }
                    })
                    .catch(error => console.error('Error checking updates:', error));
            }

            updateDateTime();
            setInterval(updateDateTime, 1000);
            setInterval(checkForUpdates, 5000); // Check setiap 5 detik
        });
    </script>
@endsection