@extends('layout.main2')

@section('content')
    <div class="container-fluid mb-3">
        <h4 class="fw-bold py-3 mb-4">Monitoring pembelajaran hari ini
            {{ \Carbon\Carbon::now()->translatedFormat('l') . ', ' . date('d-m-Y') }}</h4>
        <div class="row">
            <div class="col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-md-12">
                <div class="card">
                    <livewire:monitoring-page />
                </div>
            </div>
        </div>


    </div>
@endsection
