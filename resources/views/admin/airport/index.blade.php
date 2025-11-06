@extends('layouts.admin')
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
                                <input type="text" class="form-control" placeholder="Search" name="search"
                                    value="{{ request('search') }}">
                            </div>
                        </form>
                        <div class="dropdown">
                            <a class="btn btn-primary" href="#">
                                <i class="bi bi-plus"></i>
                                Add Airport
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-6 text-gray-900" id="arrival-flights-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Code</th>
                                <th scope="col">Name</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($airports->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center border-0">No airports available.</td>
                                </tr>
                            @endif
                            @foreach ($airports as $airport)
                                <tr>
                                    <th scope="row">{{ $loop->iteration + ($airports->currentPage() - 1) * $airports->perPage() }}</th>
                                    <td>{{ $airport->code }}</td>
                                    <td>{{ $airport->name }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.airport.edit', $airport->id) }}" class="btn btn-sm btn-warning"><i
                                                class="bi bi-sliders2"></i></a>
                                        <form action="{{ route('admin.airport.destroy', $airport->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this airport?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                    {{ $airports->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
