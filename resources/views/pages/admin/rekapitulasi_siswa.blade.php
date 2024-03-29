@extends('layout.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Rekapitulasi</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header px-3">Rekapitulasi Siswa
                    </h5>
                    <livewire:rekap-siswa />
                </div>
            </div>
        </div>
    </div>
@endsection
