@extends('layout.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Daftar Kegiatan {{ $kegiatan->nama }}</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header px-3">Kegiatan yang telah berlangsung
                    </h5>
                    <livewire:daftar-kegiatan-nara :kegiatan="$kegiatan" />
                </div>
            </div>
        </div>
    </div>
@endsection
