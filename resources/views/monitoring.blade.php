@extends('layout.main2')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Monitoring pembelajaran hari ini
            {{ \Carbon\Carbon::now()->translatedFormat('l') . ', ' . date('d-m-Y') }}</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header px-3">Informasi pembelajaran
                    </h5>
                    <livewire:monitoring-page />
                </div>
            </div>
        </div>


    </div>
@endsection
