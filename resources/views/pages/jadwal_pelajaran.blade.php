@extends('layout.datatable')
@section('datatable')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Jadwal Pelajaran</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header px-3">Jadwal pelajaran berdasarkan kelas</h5>
                    <livewire:tabel-jadwal-pelajaran />
                </div>
                <!--/ Striped Rows -->
            </div>
        </div>
    </div>
@endsection
