@extends('master')
@section('contents')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('province.show', optional($city->province)->code) }}">Prov. {{ optional($city->province)->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $city->name }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Districts</h5>
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalCreate">Generate All Districs</button>
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
                        <th>Total Schools</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($city->districts as $district)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $district->name }}</td>
                            <td>{{ $district->code }}</td>
                            <td>{{ $district->total_school }}</td>
                            <td>
                                <a href="{{ route('district.show', $district->code) }}" class="btn btn-sm btn-secondary">Show</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <hr>
            <div class="d-flex justify-content-between">
                <form action="{{ route('district.generate-by-city', $city->code) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary">Generate All Districts Of {{ $city->name }}</button>
                </form>

                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalGenerateByCity">Create All Schools</button>
            </div>
        </div>
    </div>

    <x-modal modal-id="modalCreate" title="Create All Districts" url="{{ route('district.store') }}" method="POST">
        <div class="mb-3">
            <label for="token" class="form-label">Token</label>
            <input type="password" class="form-control" name="token" id="token" placeholder="Token" required>
        </div>
    </x-modal>

    <x-modal modal-id="modalGenerateByCity" title="Create School Data" url="{{ route('generate-school-data.city', $city->code) }}" method="POST">
        <div class="mb-3">
            <label for="token" class="form-label">Token</label>
            <input type="password" class="form-control" name="token" id="token" placeholder="Token" required>
        </div>
    </x-modal>
@endsection