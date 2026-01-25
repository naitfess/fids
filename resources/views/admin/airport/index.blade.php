@extends('layouts.admin')
@section('css')
@endsection
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Airports') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 pb-0">
                    @include('include.alert')
                </div>
                <div class="p-6 pb-0 d-flex justify-content-end align-items-center">
                    <div class="d-flex gap-2">
                        <form action="" class="d-flex gap-2">
                            <div class="min-width-250">
                                <input type="text" class="form-control" placeholder="Search by name" name="search"
                                    value="{{ request('search') }}">
                            </div>
                        </form>
                        <div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAirport">
                                <i class="bi bi-plus"></i>
                                Add Airport
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-6 text-gray-900">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Code</th>
                                <th scope="col">Name</th>
                                {{-- <th scope="col">Area Code</th>
                                <th scope="col">Latitude</th>
                                <th scope="col">Longitude</th> --}}
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($airports->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center border-0">No airports available.</td>
                                </tr>
                            @endif
                            @foreach ($airports as $airport)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $airport->code }}</td>
                                    <td>{{ $airport->name }}</td>
                                    {{-- <td>{{ $airport->area_code }}</td>
                                    <td>{{ $airport->latitude }}</td>
                                    <td>{{ $airport->longitude }}</td> --}}
                                    <td class="text-end">
                                        <button data-bs-toggle="modal" data-bs-target="#editAirport"
                                            data-id="{{ $airport->id }}" data-code="{{ $airport->code }}"
                                            data-name="{{ $airport->name }}" data-area-code="{{ $airport->area_code }}"
                                            data-latitude="{{ $airport->latitude }}"
                                            data-longitude="{{ $airport->longitude }}"
                                            class="btn btn-sm btn-warning edit-airport-button">
                                            <i class="bi bi-sliders2"></i>
                                        </button>
                                        <form action="{{ route('admin.airport.destroy', ['airportId' => $airport->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this Airport?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $airports->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add Airport Modal -->
    <div class="modal fade" id="addAirport" tabindex="-1" aria-labelledby="addAirportLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-6" id="addAirportLabel">Add Airport</h1>
                </div>
                <div class="modal-body pt-0">
                    <form method="POST" id="addAirportForm" action="{{ route('admin.airport.store') }}">
                        @csrf
                        <div class="">
                            <label for="code" class="form-label text-sm">Code</label>
                            <input type="text" class="form-control" id="code" placeholder="Airport Code"
                                name="code" value="{{ old('code') }}">
                            <div class="text-sm text-danger" id="codeError"></div>
                        </div>
                        <div class="mt-3">
                            <label for="name" class="form-label text-sm">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Airport Name"
                                name="name" value="{{ old('name') }}">
                            <div class="text-sm text-danger" id="nameError"></div>
                        </div>
                        {{-- <div class="mt-3">
                            <label for="area_code" class="form-label text-sm">Area Code</label>
                            <input type="text" class="form-control" id="area_code" placeholder="Area Code"
                                name="area_code" value="{{ old('area_code') }}">
                            <div class="text-sm text-danger" id="area_codeError"></div>
                        </div>
                        <div class="mt-3">
                            <label for="latitude" class="form-label text-sm">Latitude</label>
                            <input type="text" class="form-control" id="latitude" placeholder="Latitude"
                                name="latitude" value="{{ old('latitude') }}">
                            <div class="text-sm text-danger" id="latitudeError"></div>
                        </div>
                        <div class="mt-3">
                            <label for="longitude" class="form-label text-sm">Longitude</label>
                            <input type="text" class="form-control" id="longitude" placeholder="Longitude"
                                name="longitude" value="{{ old('longitude') }}">
                            <div class="text-sm text-danger" id="longitudeError"></div>
                        </div> --}}
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="submitAddAirport">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Airport Modal -->
    <div class="modal fade" id="editAirport" tabindex="-1" aria-labelledby="editAirportLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-6" id="editAirportLabel">Edit Airport</h1>
                </div>
                <div class="modal-body pt-0">
                    <form method="POST" id="editAirportForm" action="">
                        @method('PUT')
                        @csrf
                        <div class="">
                            <label for="code-edit" class="form-label text-sm">Code</label>
                            <input type="text" class="form-control" id="code-edit" placeholder="Airport Code"
                                name="code" value="{{ old('code') }}">
                            <div class="text-sm text-danger" id="codeError-edit"></div>
                        </div>
                        <div class="mt-3">
                            <label for="name-edit" class="form-label text-sm">Name</label>
                            <input type="text" class="form-control" id="name-edit" placeholder="Airport Name"
                                name="name" value="{{ old('name') }}">
                            <div class="text-sm text-danger" id="nameError-edit"></div>
                        </div>
                        {{-- <div class="mt-3">
                            <label for="area_code-edit" class="form-label text-sm">Area Code</label>
                            <input type="text" class="form-control" id="area_code-edit" placeholder="Area Code"
                                name="area_code" value="{{ old('area_code') }}">
                            <div class="text-sm text-danger" id="area_codeError-edit"></div>
                        </div>
                        <div class="mt-3">
                            <label for="latitude-edit" class="form-label text-sm">Latitude</label>
                            <input type="text" class="form-control" id="latitude-edit" placeholder="Latitude"
                                name="latitude" value="{{ old('latitude') }}">
                            <div class="text-sm text-danger" id="latitudeError-edit"></div>
                        </div>
                        <div class="mt-3">
                            <label for="longitude-edit" class="form-label text-sm">Longitude</label>
                            <input type="text" class="form-control" id="longitude-edit" placeholder="Longitude"
                                name="longitude" value="{{ old('longitude') }}">
                            <div class="text-sm text-danger" id="longitudeError-edit"></div>
                        </div> --}}
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="submitEditAirport">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Add Airport
            $('#submitAddAirport').on('click', function(e) {
                e.preventDefault();
                var form = $('#addAirportForm')[0];

                // Clear previous error messages
                $('#codeError').text('');
                $('#nameError').text('');
                // $('#area_codeError').text('');
                // $('#latitudeError').text('');
                // $('#longitudeError').text('');

                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            location.reload();
                        } else {
                            $.each(data.errors, function(prefix, val) {
                                $('#' + prefix + 'Error').text(val[0]);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(prefix, val) {
                                $('#' + prefix + 'Error').text(val[0]);
                            });
                        } else {
                            alert('An unexpected error occurred. Please try again.');
                        }
                    }
                });
            });

            // Edit Airport - Load data ke modal
            $('.edit-airport-button').on('click', function() {
                var id = $(this).data('id');
                var code = $(this).data('code');
                var name = $(this).data('name');
                // var areaCode = $(this).data('area-code');
                // var latitude = $(this).data('latitude');
                // var longitude = $(this).data('longitude');

                $('#code-edit').val(code);
                $('#name-edit').val(name);
                // $('#area_code-edit').val(areaCode);
                // $('#latitude-edit').val(latitude);
                // $('#longitude-edit').val(longitude);

                $('#editAirportForm').attr('action', '/admin/airport/' + id);
            });

            // Update Airport
            $('#submitEditAirport').on('click', function(e) {
                e.preventDefault();
                var form = $('#editAirportForm')[0];

                // Clear previous error messages
                $('#codeError-edit').text('');
                $('#nameError-edit').text('');
                // $('#area_codeError-edit').text('');
                // $('#latitudeError-edit').text('');
                // $('#longitudeError-edit').text('');

                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            location.reload();
                        } else {
                            $.each(data.errors, function(prefix, val) {
                                $('#' + prefix + 'Error-edit').text(val[0]);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(prefix, val) {
                                $('#' + prefix + 'Error-edit').text(val[0]);
                            });
                        } else {
                            alert('An unexpected error occurred. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
@endsection