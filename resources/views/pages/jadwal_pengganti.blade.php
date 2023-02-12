@extends('layout.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Jadwal Pengganti</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header px-3">Daftar jadwal pengganti</h5>
                    <p class="mx-3">Dilakukan bila guru berhalangan hadir dan
                        menggantinya di hari lain !</p>
                    <livewire:tabel-jadwal-pengganti />
                </div>
            </div>
        </div>
    </div>
@endsection
