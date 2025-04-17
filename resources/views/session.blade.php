<!-- Alert for success messages -->
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Alert for session error messages -->
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<!-- Alert for validation errors -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif