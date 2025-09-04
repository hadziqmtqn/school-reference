@extends('master')
@section('contents')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Prov. {{ $province->name }}</li>
        </ol>
    </nav>

    <div class="card">
        <h5 class="card-header">City</h5>
        <div class="card-body">
            @include('session')
            <div class="table-responsive">
                <table class="table table-striped w-100 text-nowrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Total District</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($province->cities as $city)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $city->name }}</td>
                            <td>{{ $city->code }}</td>
                            <th>{{ $city->districts->count() }}</th>
                            <td>
                                <a href="{{ route('city.show', $city->code) }}" class="btn btn-sm btn-secondary">Show</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <hr>
            <div class="d-flex justify-content-between">
                @if($province->cities->count() > 0)
                    <form action="{{ route('district.store', $province->code) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary">Generate All Districts Of {{ $province->name }}</button>
                    </form>
                @endif

                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalCreateDistrict">Create All District</button>
            </div>
        </div>
    </div>

    <x-modal modal-id="modalCreateDistrict" title="Create District Data" url="{{ route('district.store-all') }}" method="POST">
        <div class="mb-3">
            <label for="token" class="form-label">Token</label>
            <input type="password" class="form-control" name="token" id="token" placeholder="Token" required>
        </div>
    </x-modal>
@endsection