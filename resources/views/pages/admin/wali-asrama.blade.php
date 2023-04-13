@extends('layout.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data Master /</span> Data Wali Asrama</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header px-3">Daftar Wali Asrama</h5>
                    <livewire:tabel-wali-asrama />
                </div>
                <!--/ Striped Rows -->
            </div>
        </div>
    </div>
@endsection
