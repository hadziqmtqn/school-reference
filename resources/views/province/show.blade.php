@extends('master')
@section('contents')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Prov. {{ $province->name }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Province</h5>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCreate">Create All Cities</button>
        </div>
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
                <form action="{{ route('city.store', $province->code) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary">Generate All Cities Of {{ $province->name }}</button>
                </form>

                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalGenerateByProvince">Create All Schools</button>
            </div>
        </div>
    </div>

    <x-modal modal-id="modalCreate" title="Create City Data" url="{{ route('city.store') }}" method="POST">
        <div class="mb-3">
            <label for="token" class="form-label">Token</label>
            <input type="password" class="form-control" name="token" id="token" placeholder="Token" required>
        </div>
    </x-modal>

    <x-modal modal-id="modalGenerateByProvince" title="Create School Data" url="{{ route('generate-school-data.province', $province->code) }}" method="POST"></x-modal>
@endsection