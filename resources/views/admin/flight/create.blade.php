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
                <div class="p-6 text-gray-900">
                    <form class="row g-3" action="{{ route('admin.flight.store') }}" method="POST">
                        @csrf
                        <div class="col-md-6">
                            <label for="airlane" class="form-label">Airlane</label>
                            <select id="airlane" class="form-select" name="airlane">
                                <option selected disabled>Select Airlane</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('airlane')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="flight_number" class="form-label">Flight Number</label>
                            <input type="text" class="form-control" id="flight_number" placeholder="Flight Number"
                                name="flight_number" value="{{ old('flight_number') }}">
                            @error('flight_number')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="flight_type" class="form-label">Flight Type</label>
                            <select id="flight_type" class="form-select" name="flight_type">
                                <option selected disabled>Select Flight Type</option>
                                <option {{ old('flight_type', $flight_type) === 'arrival' ? 'selected' : '' }}
                                    value="arrival">Arrival</option>
                                <option {{ old('flight_type', $flight_type) === 'departure' ? 'selected' : '' }}
                                    value="departure">Departure</option>
                            </select>
                            @error('flight_type')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6" id="origin-container">
                            <label for="origin" class="form-label">Origin</label>
                            <select id="origin" class="form-select" name="origin">
                                <option selected disabled>Select Origin</option>
                                @foreach ($airports as $airport)
                                    <option value="{{ $airport->id }}"
                                        {{ old('origin') == $airport->id ? 'selected' : '' }}>{{ $airport->code }} -
                                        {{ $airport->name }}</option>
                                @endforeach
                            </select>
                            @error('origin')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6" id="destination-container">
                            <label for="destination" class="form-label">Destination</label>
                            <select id="destination" class="form-select" name="destination">
                                <option selected disabled>Select Destination</option>
                                @foreach ($airports as $airport)
                                    <option value="{{ $airport->id }}"
                                        {{ old('destination') == $airport->id ? 'selected' : '' }}>
                                        {{ $airport->code }} - {{ $airport->name }}</option>
                                @endforeach
                            </select>
                            @error('destination')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6" id="gate-container">
                            <label for="gate" class="form-label">Gate</label>
                            <select id="gate" class="form-select" name="gate">
                                <option selected disabled>Select Gate</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('gate') == $i ? 'selected' : '' }}>
                                        {{ $i }}</option>
                                @endfor
                            </select>
                            @error('gate')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="scheduled_time" class="form-label">Scheduled Time</label>
                            <input type="datetime-local" class="form-control" id="scheduled_time" name="scheduled_time"
                                value="{{ old('scheduled_time') }}">
                            @error('scheduled_time')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <a href="{{ route('admin.flight.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    //script to toggle origin and destination based on flight type
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const flightTypeSelect = document.getElementById('flight_type');
            const originContainer = document.getElementById('origin-container');
            const destinationContainer = document.getElementById('destination-container');
            const gateContainer = document.getElementById('gate-container');

            function toggleFields() {
                const selectedType = flightTypeSelect.value.toLowerCase();
                if (selectedType === 'arrival') {
                    originContainer.style.display = 'block';
                    destinationContainer.style.display = 'none';
                    gateContainer.style.display = 'none';
                } else if (selectedType === 'departure') {
                    originContainer.style.display = 'none';
                    destinationContainer.style.display = 'block';
                    gateContainer.style.display = 'block';
                } else {
                    originContainer.style.display = 'none';
                    destinationContainer.style.display = 'none';
                }
            }
            flightTypeSelect.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
@endsection
