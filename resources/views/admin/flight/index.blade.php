@extends('layouts.admin')
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Flight') }}
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
                                <button type="button" class="nav-link text-black" id="arrival-tab">Arrival</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link text-black" id="departure-tab">Departure</button>
                            </li>
                        </ul>
                    </div>
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('admin.flight.index') }}" class="d-flex gap-2">
                            <input type="hidden" name="tab" id="tab-input" value="{{ request('tab', 'arrival') }}">
                            <div class="min-width-250">
                                <input type="text" class="form-control" placeholder="Search by flight number"
                                    name="search" value="{{ request('search') }}">
                            </div>
                            <div>
                                <select class="form-select" name="scheduled_time">
                                    <option value="today"
                                        {{ request('scheduled_time', 'today') === 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="alltime" {{ request('scheduled_time') === 'alltime' ? 'selected' : '' }}>
                                        All Time</option>
                                </select>
                            </div>
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary" data-bs-toggle="dropdown"
                                    aria-expanded="false" data-bs-auto-close="outside">
                                    <i class="bi bi-funnel"></i>
                                    Filter
                                </button>
                                <div class="dropdown-menu px-3 py-2">
                                    <div class="mb-1">
                                        <label for="gate" class="form-label mb-0 text-sm">Gate</label>
                                        <select class="form-select form-select-sm" name="gate">
                                            <option value="" {{ request('gate') === '' ? 'selected' : '' }}>All Gate
                                            </option>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}"
                                                    {{ request('gate') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="mb-1">
                                        <label for="status" class="form-label mb-0 text-sm">Status</label>
                                        <select class="form-select form-select-sm" name="status">
                                            <option value="" {{ request('status') === '' ? 'selected' : '' }}>All Status
                                            </option>
                                            @foreach (['Check-in Open', 'Check-in Closed', 'Boarding', 'Final Call', 'Departed', 'Delayed', 'Cancelled'] as $st)
                                                <option value="{{ $st }}"
                                                    {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="text-end mt-2">
                                        <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="dropdown">
                            <button class="btn btn-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-plus"></i>
                                Add Flight
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.flight.create', ['flight_type' => 'arrival']) }}">Arrival</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.flight.create', ['flight_type' => 'departure']) }}">Departure</a>
                                </li>
                            </ul>
                        </div>
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
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($arrival_flights->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center border-0">No arrival flights available.</td>
                                </tr>
                            @endif
                            @foreach ($arrival_flights as $arrival_flight)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $arrival_flight->flight_number }}</td>
                                    <td>{{ @$arrival_flight->origin->code }}</td>
                                    <td>{{ $arrival_flight->scheduled_time ? \Illuminate\Support\Carbon::parse($arrival_flight->scheduled_time)->format('d-m-Y H:i') : '' }}
                                    </td>
                                    <td>{{ $arrival_flight->gate }}</td>
                                    <td>{{ $arrival_flight->status }}
                                        {{ $arrival_flight->delayed_until ? ' Until ' . \Illuminate\Support\Carbon::parse($arrival_flight->delayed_until)->format('H:i') : '' }}
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-warning change-status-button" data-bs-toggle="modal"
                                            data-bs-target="#changeStatusModal" data-flight-id="{{ $arrival_flight->id }}"
                                            data-status="{{ $arrival_flight->status }}"
                                            data-delayed-until="{{ $arrival_flight->delayed_until ?? '' }}">
                                            <i class="bi bi-three-dots"></i>
                                        </a>
                                        <a href="{{ route('admin.flight.edit', $arrival_flight->id) }}"
                                            class="btn btn-sm btn-warning"><i class="bi bi-sliders2"></i></a>
                                        <form action="{{ route('admin.flight.destroy', $arrival_flight->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this flight?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
                                <th scope="col"></th>
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
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $departure_flight->flight_number }}</td>
                                    <td>{{ @$departure_flight->destination->code }}</td>
                                    <td>{{ $departure_flight->scheduled_time ? \Illuminate\Support\Carbon::parse($departure_flight->scheduled_time)->format('d-m-Y H:i') : '' }}
                                    </td>
                                    <td>{{ $departure_flight->gate }}</td>
                                    <td>{{ $departure_flight->status }}</td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-warning change-status-button" data-bs-toggle="modal"
                                            data-bs-target="#changeStatusModal"
                                            data-flight-id="{{ $departure_flight->id }}"
                                            data-status="{{ $departure_flight->status }}"
                                            data-delayed-until="{{ $departure_flight->delayed_until ?? '' }}">
                                            <i class="bi bi-three-dots"></i>
                                        </a>
                                        <a href="{{ route('flight.edit', $departure_flight->id) }}"
                                            class="btn btn-sm btn-warning"><i class="bi bi-sliders2"></i></a>
                                        <form action="{{ route('flight.destroy', $departure_flight->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this flight?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                    {{ $departure_flights->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-6" id="changeStatusModalLabel">Change Flight Status</h1>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body pt-0">
                    <form method="POST" id="changeStatusForm">
                        @csrf
                        @method('PATCH')
                        <div class="form-floating">
                            <select class="form-select" id="floatingSelect" aria-label="Status" name="status">
                                <option selected disabled>Select Status</option>
                                <option value="Check-in Open">Check-in Open</option>
                                <option value="Check-in Closed">Check-in Closed</option>
                                <option value="Boarding">Boarding</option>
                                <option value="Final Call">Final Call</option>
                                <option value="Departed">Departed</option>
                                <option value="Delayed">Delayed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                            <label for="floatingSelect">Status</label>
                            <div class="text-sm text-danger" id="statusError"></div>
                        </div>
                        <div class="form-floating mt-3 d-none" id="delayed-until-container">
                            <input type="datetime-local" class="form-control" id="floatingInput" name="delayed_until">
                            <label for="floatingInput">Delayed Until</label>
                            <div class="text-sm text-danger" id="delayedUntilError"></div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
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
            const tabInput = document.getElementById('tab-input');

            const setTab = (tab) => {
                if (tab === 'departure') {
                    arrivalTable.style.display = 'none';
                    departureTable.style.display = 'block';
                    departureTab.classList.add('active');
                    arrivalTab.classList.remove('active');
                } else {
                    arrivalTable.style.display = 'block';
                    departureTable.style.display = 'none';
                    arrivalTab.classList.add('active');
                    departureTab.classList.remove('active');
                    tab = 'arrival';
                }
                if (tabInput) {
                    tabInput.value = tab;
                }
            };

            // initial tab from query
            setTab(tabInput?.value || 'arrival');

            arrivalTab.addEventListener('click', function(e) {
                e.preventDefault();
                setTab('arrival');
            });

            departureTab.addEventListener('click', function(e) {
                e.preventDefault();
                setTab('departure');
            });

            // Handle Change Status Modal
            const selectedStatus = document.getElementById('floatingSelect');
            const delayedUntilContainer = document.getElementById('delayed-until-container');
            const delayedInput = document.querySelector('#delayed-until-container input[name="delayed_until"]');
            const statusErrorDiv = document.getElementById('statusError');
            const delayedUntilErrorDiv = document.getElementById('delayedUntilError');

            selectedStatus.addEventListener('change', function() {
                if (this.value === 'Delayed') {
                    delayedUntilContainer.classList.remove('d-none');
                } else {
                    delayedUntilContainer.classList.add('d-none');
                }
                // clear errors when user changes selection
                statusErrorDiv.textContent = '';
                delayedUntilErrorDiv.textContent = '';
            });

            //Handle change status button
            const changeStatusButtons = document.querySelectorAll('.change-status-button');
            const changeStatusForm = document.getElementById('changeStatusForm');

            changeStatusButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const flightId = this.dataset.flightId;
                    const currentStatus = this.dataset.status;
                    const delayedUntil = this.dataset.delayedUntil;
                    if (delayedUntil) {
                        delayedInput.value = delayedUntil.replace(' ', 'T');
                    } else {
                        delayedInput.value = '';
                    }
                    const actionUrl =
                        "{{ route('admin.flight.changeStatus', ['flightId' => ':id']) }}"
                        .replace(':id', flightId);
                    changeStatusForm.action = actionUrl;
                    let matched = false;
                    for (const opt of selectedStatus.options) {
                        const optVal = (opt.value || '').trim().toLowerCase();
                        const optText = (opt.text || '').trim().toLowerCase();
                        const cur = (currentStatus || '').toLowerCase();
                        if ((optVal && optVal === cur) || (optText && optText === cur)) {
                            selectedStatus.value = opt.value || opt.text.trim();
                            matched = true;
                            break;
                        }
                    }

                    if (!matched) {
                        selectedStatus.selectedIndex = 0;
                    }

                    selectedStatus.dispatchEvent(new Event('change'));
                    // clear previous validation UI
                    selectedStatus.classList.remove('is-invalid');
                    statusErrorDiv.textContent = '';
                    delayedInput.classList.remove('is-invalid');
                    delayedUntilErrorDiv.textContent = '';
                });
            });

            // AJAX submit
            changeStatusForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // clear previous validation UI
                statusErrorDiv.textContent = '';
                delayedUntilErrorDiv.textContent = '';

                const tokenInput = changeStatusForm.querySelector('input[name="_token"]');
                const csrfToken = tokenInput ? tokenInput.value : '';

                const formData = new FormData(changeStatusForm);

                fetch(changeStatusForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData,
                    credentials: 'same-origin'
                }).then(async (res) => {
                    if (res.status === 422) {
                        const errors = await res.json();
                        // show validation errors beside fields
                        if (errors.status) {
                            statusErrorDiv.textContent = errors.status.join(' ');
                        }
                        if (errors.delayed_until) {
                            delayedUntilErrorDiv.textContent = errors.delayed_until.join(' ');
                            // ensure delayed input visible
                            delayedUntilContainer.classList.remove('d-none');
                        }
                    } else if (res.ok) {
                        // success: server already flashed session, reload to show flash
                        try {
                            // hide modal first if using bootstrap modal
                            const modalEl = document.getElementById('changeStatusModal');
                            const bsModal = bootstrap.Modal.getInstance(modalEl);
                            if (bsModal) bsModal.hide();
                        } catch (err) {
                            // ignore
                        }
                        // short delay to allow modal to hide
                        setTimeout(() => {
                            window.location.reload();
                        }, 150);
                    } else {
                        // unexpected error - try to parse message
                        const data = await res.text();
                        console.error('Unexpected response:', data);
                        alert('An unexpected error occurred.');
                    }
                }).catch((err) => {
                    console.error('Request failed', err);
                    alert('Request failed. Check your connection.');
                });
            });
        });
    </script>
@endsection
