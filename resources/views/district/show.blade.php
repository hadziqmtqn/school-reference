@extends('master')
@section('contents')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('province.show', optional(optional($district->city)->province)->code) }}">Prov. {{ optional(optional($district->city)->province)->name }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('city.show', optional($district->city)->code) }}">{{ optional($district->city)->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $district->name }}</li>
        </ol>
    </nav>

    <div class="card">
        <h5 class="card-header">District</h5>
        <div class="card-body">
            @include('session')
            <div class="table-responsive">
                <table class="table table-striped w-100 text-nowrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>NPSM</th>
                        <th>Address</th>
                        <th>Village</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($district->schools as $school)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $school->name }}</td>
                            <td><a href="{{ url('https://referensi.data.kemdikbud.go.id/pendidikan/npsn/' . $school->npsn) }}" target="_blank">{{ $school->npsn }}</a></td>
                            <td>{{ $school->street }}</td>
                            <td>{{ $school->village }}</td>
                            <td>{{ $school->status }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection