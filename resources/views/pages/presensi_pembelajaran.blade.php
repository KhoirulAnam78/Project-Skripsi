@extends('layout.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Presensi Pembelajaran</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header px-3">Daftar kegiatan pembelajaran dikelas hari
                        <b>{{ $day }}</b>
                    </h5>
                    <livewire:input-presensi />
                </div>
            </div>
        </div>
    </div>
@endsection
