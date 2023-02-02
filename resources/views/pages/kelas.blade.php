@extends('layout.datatable')
@section('datatable')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data Master /</span> Data Kelas</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header px-3">Daftar Kelas</h5>
                    <livewire:tabel-kelas />
                </div>
                <!--/ Striped Rows -->
            </div>
        </div>
    </div>
@endsection
