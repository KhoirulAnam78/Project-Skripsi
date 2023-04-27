@extends('layout.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Jadwal Kegiatan</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header px-3">Jadwal kegiatan berdasarkan angkatan</h5>
                    <livewire:tabel-jadwal-kegiatan />
                </div>
                <!--/ Striped Rows -->
            </div>
        </div>
    </div>
@endsection
