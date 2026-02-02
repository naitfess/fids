@extends('layouts.admin')
@section('css')
@endsection
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Airlines') }}
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
                            <a class="btn btn-primary" href="{{ route('admin.user.create') }}" data-bs-toggle="modal"
                                data-bs-target="#addUser">
                                <i class="bi bi-plus"></i>
                                Add Airline
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-6 text-gray-900" id="arrival-flights-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Logo</th>
                                <th scope="col">Name</th>
                                <th scope="col">Role</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center border-0">No airlines available.</td>
                                </tr>
                            @endif
                            @foreach ($users as $user)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        <div class="logo-sm d-flex align-items-center">
                                            <img src="{{ asset('uploads/' . $user->logo) }}" alt="">
                                        </div>
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td class="text-end">
                                        <a data-bs-toggle="modal" data-bs-target="#editUser" data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                            data-logo="{{ asset('uploads/' . $user->logo) }}"
                                            class="btn btn-sm btn-warning edit-user-button"><i
                                                class="bi bi-sliders2"></i></a>
                                        @if (auth()->user()->role === 'admin' && auth()->user()->id !== $user->id)
                                            <form action="{{ route('admin.user.destroy', ['userId' => $user->id]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this User?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-6" id="addUserLabel">Add Airline</h1>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body pt-0">
                    <form method="POST" id="addUserForm" action="{{ route('admin.user.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="">
                            <label for="name" class="form-label text-sm">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Airline Name"
                                name="name" value="{{ old('name') }}">
                            <div class="text-sm text-danger" id="nameError"></div>
                        </div>
                        <div class="mt-3">
                            <label for="email" class="form-label text-sm">Email</label>
                            <input type="text" class="form-control" id="email" placeholder="Email" name="email"
                                value="{{ old('email') }}">
                            <div class="text-sm text-danger" id="emailError"></div>
                        </div>
                        <div class="mt-3">
                            <label for="password" class="form-label text-sm">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Password"
                                name="password" value="{{ old('password') }}">
                            <div class="text-sm text-danger" id="passwordError"></div>
                        </div>
                        <div class="mt-3">
                            <label for="logo" class="form-label text-sm">Logo</label>
                            <small>PNG, 300x300</small>
                            <input type="file" class="dropify" accept="image/*" data-default-file=""
                                name="logo" />
                            <div class="text-sm text-danger" id="logoError"></div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="submitAddUser">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editUser" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-6" id="editUserLabel">Edit Airline</h1>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body pt-0">
                    <form method="POST" id="editUserForm" action="" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="">
                            <label for="name-edit" class="form-label text-sm">Name</label>
                            <input type="text" class="form-control" id="name-edit" placeholder="Airline Name"
                                name="name" value="{{ old('name') }}">
                            <div class="text-sm text-danger" id="nameError-edit"></div>
                        </div>
                        <div class="mt-3">
                            <label for="email-edit" class="form-label text-sm">Email</label>
                            <input type="text" class="form-control" id="email-edit" placeholder="Email"
                                name="email" value="{{ old('email') }}">
                            <div class="text-sm text-danger" id="emailError-edit"></div>
                        </div>
                        {{-- <div class="mt-3">
                            <label for="password-edit" class="form-label text-sm">Password</label>
                            <input type="password" class="form-control" id="password-edit" placeholder="Password"
                                name="password" value="{{ old('password') }}">
                            <div class="text-sm text-danger" id="passwordError"></div>
                        </div> --}}
                        <div class="mt-3">
                            <label for="logo-edit" class="form-label text-sm">Logo</label>
                            <input type="file" id="logo-edit" class="dropify" accept="image/*" data-default-file=""
                                name="logo" />
                            <div class="text-sm text-danger" id="logoError-edit"></div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="submitEditUser">Submit</button>
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
            $('.dropify').dropify();

            $('#submitAddUser').on('click', function(e) {
                e.preventDefault();
                var form = $('#addUserForm')[0]; // Ambil form dengan ID addUserForm

                // Clear previous error messages
                $('#nameError').text('');
                $('#emailError').text('');
                $('#passwordError').text('');
                $('#logoError').text('');

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
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            // Reload halaman
                            location.reload();
                        } else {
                            // Tampilkan error validasi
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

            $('.edit-user-button').on('click', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var email = $(this).data('email');
                var logo = $(this).data('logo');

                $('#name-edit').val(name);
                $('#email-edit').val(email);
                // $('#password-edit').val(password);

                var editDropify = $('#logo-edit').dropify({
                    defaultFile: logo
                });
                editDropify = editDropify.data('dropify');
                editDropify.resetPreview();
                editDropify.clearElement();
                editDropify.settings.defaultFile = logo;
                editDropify.destroy();
                editDropify.init();

                $('#editUserForm').attr('action', '/admin/user/' + id);
            });

            $('#submitEditUser').on('click', function(e) {
                e.preventDefault();
                var form = $('#editUserForm')[0]; // Ambil form dengan ID editUserForm

                // Clear previous error messages
                $('#nameError-edit').text('');
                $('#emailError-edit').text('');
                $('#logoError-edit').text('');

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
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
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
