@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Running Text') }}
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
                                <input type="text" class="form-control" placeholder="Search by text" name="search"
                                    value="{{ request('search') }}">
                            </div>
                        </form>
                        <div>
                            <a class="btn btn-primary" href="{{ route('admin.running-text.index') }}" data-bs-toggle="modal"
                                data-bs-target="#addRunningText">
                                <i class="bi bi-plus"></i>
                                Add Running Text
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6 text-gray-900">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Text</th>
                                <th scope="col" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($runningTexts->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center border-0">No running texts available.</td>
                                </tr>
                            @endif

                            @foreach ($runningTexts as $runningText)
                                <tr>
                                    <th scope="row">{{ $runningTexts->firstItem() + $loop->index }}</th>
                                    <td>{{ $runningText->text }}</td>
                                    <td class="text-end">
                                        <a data-bs-toggle="modal" data-bs-target="#editRunningText"
                                            data-id="{{ $runningText->id }}" data-text="{{ $runningText->text }}"
                                            class="btn btn-sm btn-warning edit-running-text-button"><i
                                                class="bi bi-sliders2"></i></a>

                                        <form
                                            action="{{ route('admin.running-text.destroy', ['runningTextId' => $runningText->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this running text?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $runningTexts->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRunningText" tabindex="-1" aria-labelledby="addRunningTextLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-6" id="addRunningTextLabel">Add Running Text</h1>
                </div>
                <div class="modal-body pt-0">
                    <form method="POST" id="addRunningTextForm" action="{{ route('admin.running-text.store') }}">
                        @csrf
                        <div>
                            <label for="running-text" class="form-label text-sm">Text</label>
                            <textarea class="form-control" id="running-text" name="text" rows="3" placeholder="Input running text">{{ old('text') }}</textarea>
                            <div class="text-sm text-danger" id="textError"></div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="submitAddRunningText">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRunningText" tabindex="-1" aria-labelledby="editRunningTextLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-6" id="editRunningTextLabel">Edit Running Text</h1>
                </div>
                <div class="modal-body pt-0">
                    <form method="POST" id="editRunningTextForm" action="">
                        @method('PUT')
                        @csrf
                        <div>
                            <label for="running-text-edit" class="form-label text-sm">Text</label>
                            <textarea class="form-control" id="running-text-edit" name="text" rows="3" placeholder="Input running text"></textarea>
                            <div class="text-sm text-danger" id="textError-edit"></div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="submitEditRunningText">Submit</button>
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
            $('#submitAddRunningText').on('click', function(e) {
                e.preventDefault();
                var form = $('#addRunningTextForm')[0];

                $('#textError').text('');

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
                    error: function(xhr) {
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

            $('.edit-running-text-button').on('click', function() {
                var id = $(this).data('id');
                var text = $(this).data('text');

                $('#running-text-edit').val(text);
                $('#editRunningTextForm').attr('action', '/admin/running-text/' + id);
            });

            $('#submitEditRunningText').on('click', function(e) {
                e.preventDefault();
                var form = $('#editRunningTextForm')[0];

                $('#textError-edit').text('');

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
                    error: function(xhr) {
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
