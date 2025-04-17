@extends('master')
@section('contents')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Province</h5>
            <form action="{{ route('province.store') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            </form>
        </div>
        <div class="card-body">
            <h5 class="card-title">Special title treatment</h5>
            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>
@endsection