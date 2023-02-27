@extends('layout.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Validasi Pembelajaran</span></h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header px-3">Validasi pembelajaran yang berlangsung hari ini</h5>
                    <livewire:validasi-pembelajaran />
                </div>
                <!--/ Striped Rows -->
            </div>
        </div>
    </div>
@endsection
