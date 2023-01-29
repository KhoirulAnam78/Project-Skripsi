@extends('layout.datatable')
@section('datatable')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Jadwal Guru Piket</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header">Daftar jadwal guru piket dalam satu minggu</h5>
                    <livewire:tabel-jadwal-piket />
                </div>
                <!--/ Striped Rows -->
            </div>
        </div>
    </div>
@endsection
