@extends('layouts.frontpage')
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Frontpage') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 pb-0">
                    @include('include.alert')
                </div>
                <div class="p-6 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <ul class="nav nav-underline">
                            <li class="nav-item">
                                <button class="nav-link text-black active" id="arrival-tab">Arrival</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link text-black" id="departure-tab">Departure</button>
                            </li>
                        </ul>
                    </div>
                    <div class="d-flex gap-2">
                        <form action="" class="d-flex gap-2">
                            <div class="min-width-250">
                                <input type="text" class="form-control" placeholder="Search by flight number"
                                    name="search" value="{{ request('search') }}">
                            </div>
                            <div class="">
                                <button type="button" class="btn btn-primary" data-bs-toggle="dropdown"
                                    aria-expanded="false" data-bs-auto-close="outside">
                                    <i class="bi bi-funnel"></i>
                                    Filter
                                </button>
                                <div class="dropdown-menu px-3 py-2">
                                    <div class="mb-1">
                                        <label for="gate" class="form-label mb-0 text-sm">Gate</label>
                                        <select class="form-select form-select-sm" name="gate">
                                            <option value="" selected>All Gate</option>
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                            <option value="">4</option>
                                            <option value="">5</option>
                                        </select>
                                    </div>
                                    <div class="mb-1">
                                        <label for="status" class="form-label mb-0 text-sm">Status</label>
                                        <select class="form-select form-select-sm" name="status">
                                            <option value="" selected>All Status</option>
                                            <option value="Check-in Open">Check-in Open</option>
                                            <option value="Check-in Closed">Check-in Closed</option>
                                            <option value="Boarding">Boarding</option>
                                            <option value="Final Call">Final Call</option>
                                            <option value="Departed">Departed</option>
                                            <option value="Delayed">Delayed</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="text-end mt-2">
                                        <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="p-6 text-gray-900" id="arrival-flights-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Flight Number</th>
                                <th scope="col">Origin</th>
                                <th scope="col">Scheduled</th>
                                <th scope="col">Gate</th>
                                <th scope="col">Status</th>
                                <th scope="col">Cuaca</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($arrival_flights->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center border-0">No arrival flights available.</td>
                                </tr>
                            @endif
                            @foreach ($arrival_flights as $arrival_flight)
                                <tr class="align-middle">
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $arrival_flight->flight_number }}</td>
                                    <td>{{ @$arrival_flight->origin->code }}</td>
                                    <td>{{ $arrival_flight->scheduled_time ? \Illuminate\Support\Carbon::parse($arrival_flight->scheduled_time)->format('d-m-Y H:i') : '' }}
                                    </td>
                                    <td>{{ $arrival_flight->gate }}</td>
                                    <td>{{ $arrival_flight->status }}
                                        {{ $arrival_flight->delayed_until ? ' Until ' . \Illuminate\Support\Carbon::parse($arrival_flight->delayed_until)->format('H:i') : '' }}
                                    </td>
                                    <td>
                                        @if ($arrival_flight->origin->weatherReport)
                                            <div class="d-flex align-items-center">
                                                <div class="">
                                                    <div style="width: 50px">
                                                        <img src="https://openweathermap.org/img/wn/{{ $arrival_flight->origin->weatherReport->icon }}@2x.png"
                                                            alt="">
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column ms-2">
                                                    <div>
                                                        {{ $arrival_flight->origin->weatherReport->temperature }}°C
                                                    </div>
                                                    <div>
                                                        {{ $arrival_flight->origin->weatherReport->weather }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div>
                                                -
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                    {{ $arrival_flights->links('pagination::bootstrap-5') }}
                </div>
                <div class="p-6 text-gray-900" id="departure-flights-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Flight Number</th>
                                <th scope="col">Destination</th>
                                <th scope="col">Scheduled</th>
                                <th scope="col">Gate</th>
                                <th scope="col">Status</th>
                                <th scope="col">Cuaca</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($departure_flights->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center border-0">No departure flights available.
                                    </td>
                                </tr>
                            @endif
                            @foreach ($departure_flights as $departure_flight)
                                <tr class="align-middle">
                                    <th scope="row" class="align-middle">{{ $loop->iteration }}</th>
                                    <td>{{ $departure_flight->flight_number }}</td>
                                    <td>{{ @$departure_flight->destination->code }}</td>
                                    <td>{{ $departure_flight->scheduled_time ? \Illuminate\Support\Carbon::parse($departure_flight->scheduled_time)->format('d-m-Y H:i') : '' }}
                                    </td>
                                    <td>{{ $departure_flight->gate }}</td>
                                    <td>{{ $departure_flight->status }}</td>
                                    <td>
                                        @if ($departure_flight->destination->weatherReport)
                                            <div class="d-flex align-items-center">
                                                <div class="">
                                                    <div style="width: 50px">
                                                        <img src="https://openweathermap.org/img/wn/{{ $departure_flight->destination->weatherReport->icon }}@2x.png"
                                                            alt="">
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column ms-2">
                                                    <div>
                                                        {{ $departure_flight->destination->weatherReport->temperature }}°C
                                                    </div>
                                                    <div>
                                                        {{ $departure_flight->destination->weatherReport->weather }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div>
                                                -
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                    {{ $departure_flights->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const arrivalTab = document.getElementById('arrival-tab');
            const departureTab = document.getElementById('departure-tab');
            const arrivalTable = document.getElementById('arrival-flights-table');
            const departureTable = document.getElementById('departure-flights-table');

            // Show arrival flights by default
            arrivalTable.style.display = 'block';
            departureTable.style.display = 'none';

            arrivalTab.addEventListener('click', function() {
                arrivalTable.style.display = 'block';
                departureTable.style.display = 'none';
                arrivalTab.classList.add('active');
                departureTab.classList.remove('active');
            });

            departureTab.addEventListener('click', function() {
                arrivalTable.style.display = 'none';
                departureTable.style.display = 'block';
                departureTab.classList.add('active');
                arrivalTab.classList.remove('active');
            });
        });
    </script>
@endsection
