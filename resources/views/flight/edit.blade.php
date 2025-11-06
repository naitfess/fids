<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Flight') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form class="row g-3" action="{{ route('flight.update', ['flightId' => $flight->id]) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="col-md-12">
                            <label for="flight_number" class="form-label">Flight Number</label>
                            <input type="text" class="form-control" id="flight_number" placeholder="Flight Number"
                                name="flight_number" value="{{ old('flight_number', $flight->flight_number) }}">
                            @error('flight_number')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="flight_type" class="form-label">Flight Type</label>
                            <select id="flight_type" class="form-select" name="flight_type" disabled>
                                <option selected disabled>Select Flight Type</option>
                                <option {{ old('flight_type', $flight->flight_type) === 'arrival' ? 'selected' : '' }}
                                    value="arrival">Arrival</option>
                                <option {{ old('flight_type', $flight->flight_type) === 'departure' ? 'selected' : '' }}
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
                                        {{ old('origin', $flight->origin_id) == $airport->id ? 'selected' : '' }}>{{ $airport->code }} -
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
                                        {{ old('destination', $flight->destination_id) == $airport->id ? 'selected' : '' }}>
                                        {{ $airport->code }} - {{ $airport->name }}</option>
                                @endforeach
                            </select>
                            @error('destination')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="gate" class="form-label">Gate</label>
                            <select id="gate" class="form-select" name="gate">
                                <option selected disabled>Select Gate</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('gate', $flight->gate) == $i ? 'selected' : '' }}>
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
                                value="{{ old('scheduled_time', $flight->scheduled_time) }}">
                            @error('scheduled_time')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <a href="{{ route('flight.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const flightTypeSelect = document.getElementById('flight_type');
                const originContainer = document.getElementById('origin-container');
                const destinationContainer = document.getElementById('destination-container');

                function toggleFields() {
                    const selectedType = flightTypeSelect.value.toLowerCase();
                    if (selectedType === 'arrival') {
                        originContainer.style.display = 'block';
                        destinationContainer.style.display = 'none';
                    } else if (selectedType === 'departure') {
                        originContainer.style.display = 'none';
                        destinationContainer.style.display = 'block';
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
</x-app-layout>
