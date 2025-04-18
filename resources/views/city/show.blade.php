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
        <h5 class="card-header">District</h5>
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
                            <td>{{ $district->totalSchools() }}</td>
                            <td>
                                <a href="{{ route('district.show', $district->code) }}" class="btn btn-sm btn-secondary">Show</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if($city->districts->count() > 0)
                <hr>
                <form action="{{ route('school.store', $city->code) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary">Generate All Schools Of {{ $city->name }}</button>
                </form>
            @endif
        </div>
    </div>
@endsection