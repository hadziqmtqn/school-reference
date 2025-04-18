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
            <form id="combinedForm" class="row row-cols-lg-auto g-3 align-items-center">
                <div class="col-12">
                    <label class="visually-hidden" for="search">Cari..</label>
                    <input type="search" class="form-control" name="search" value="{{ request()->get('search') }}" id="search" placeholder="Cari..">
                </div>

                <div class="col-12">
                    <label class="visually-hidden" for="formOfEducation">Bentuk Pendidikan</label>
                    <select class="form-select" id="formOfEducation" name="form-of-edu">
                        <option value="all">Pilih Semua</option>
                        @foreach($formOfEducation as $item)
                            <option value="{{ $item->slug }}" @selected(request()->get('form-of-edu') == $item->slug)>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary" onclick="submitForm(event, 'search')">Cari</button>
                    <button type="submit" class="btn btn-success" onclick="submitForm(event, 'export')">Export</button>
                </div>
            </form>
            <hr>
            <div class="table-responsive">
                <table class="table table-striped w-100 text-nowrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>NPSN</th>
                        <th>Name</th>
                        <th>Bentuk Pendidikan</th>
                        <th>Address</th>
                        <th>Village</th>
                        <th>District</th>
                        <th>City</th>
                        <th>Province</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($district->schools as $school)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a href="{{ url('https://referensi.data.kemdikbud.go.id/pendidikan/npsn/' . $school->npsn) }}" target="_blank">{{ $school->npsn }}</a></td>
                            <td>{{ $school->name }}</td>
                            <td>{{ optional($school->formOfEducation)->name }}</td>
                            <td>{{ $school->street }}</td>
                            <td>{{ $school->village }}</td>
                            <td>{{ optional($school->district)->name }}</td>
                            <td>{{ optional(optional($school->district)->city)->name }}</td>
                            <td>{{ optional(optional(optional($school->district)->city)->province)->name }}</td>
                            <td>{{ ucfirst(strtolower($school->status)) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function submitForm(event, action) {
            event.preventDefault(); // stop default submit

            const form = document.getElementById('combinedForm');

            // Hapus input CSRF jika ada
            const csrfInput = document.querySelector('#combinedForm input[name="_token"]');
            if (csrfInput) csrfInput.remove();

            if (action === 'search') {
                form.action = "{{ route('district.show', $district->code) }}";
                form.method = "GET";
            } else if (action === 'export') {
                form.action = "{{ route('school.export', $district->code) }}";
                form.method = "POST";

                // Tambahkan CSRF token hanya saat POST
                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = "{{ csrf_token() }}";
                form.appendChild(token);
            }

            form.submit();
        }
    </script>
@endsection