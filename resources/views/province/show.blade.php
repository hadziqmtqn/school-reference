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
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($province->cities as $city)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $city->name }}</td>
                            <td>{{ $city->code }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-secondary">Show</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <hr>
            <form action="#" method="post">
                @csrf
                <button type="submit" class="btn btn-primary">Generate Cities</button>
            </form>
        </div>
    </div>
@endsection