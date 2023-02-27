@extends('layout.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Jadwal Siswa</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header px-3">Jadwal kegiatan siswa di sekolah
                    </h5>
                    <livewire:jadwal-siswa />
                </div>
            </div>
        </div>
    </div>
@endsection
