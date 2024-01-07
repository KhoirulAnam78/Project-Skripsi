@extends('layout.main')

@section('css')
    <link rel="stylesheet" href="{{ url('') }}/assets/assets/vendor/libs/apex-charts/apex-charts.css" />
@endsection

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Selamat Datang
                                    @php
                                        if (Auth::user()->role === 'admin') {
                                            echo 'Administrator';
                                        } else {
                                            echo Auth::user()->guru->nama;
                                        }
                                    @endphp
                                </h5>
                                <p class="mb-4">
                                    Sistem informasi monitoring merupakan sistem yang bertujuan untuk memantau kegiatan
                                    pembelajaran yang dilakukan siswa di SMAN Titian Teras H. Abdurrahman Sayoeti Jambi.
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="{{ url('') }}/assets/assets/img/illustrations/man-with-laptop-light.png"
                                    height="140" alt="View Badge User"
                                    data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                    data-app-light-img="illustrations/man-with-laptop-light.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class='bx bxs-chalkboard' style="font-size: 40px;"></i>
                                {{-- <i class='bx bx-desktop' ></i> --}}
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Guru Aktif</span>
                        <h3 class="card-title mb-2">{{ $guruAktif }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class='bx bxs-user' style="font-size: 40px;"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-2">Siswa Aktif</span>
                        <h3 class="card-title text-nowrap mb-2">{{ $siswaAktif }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class='bx bxs-group' style="font-size:40px"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-2">Kelas Aktif</span>
                        <h3 class="card-title text-nowrap mb-2">{{ $kelasAktif }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class='bx bxs-user-detail' style="font-size: 40px"></i>
                            </div>
                        </div>
                        <span class="fw-semibold
                                    d-block mb-2">Wali Asrama Aktif</span>
                        <h3 class="card-title text-nowrap mb-2">{{ $waliAsrama }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <livewire:persentase-dashboard />
        {{-- <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12 order-0 mb-2">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Grafik</h5>
                        </div>
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12 order-1 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Grafik Pembelajaran Tidak Terlaksana</h5>
                            <small class="text-muted">Pembelajaran yang digantikan guru piket</small>
                        </div>
                    </div>
                    <div class="card-body px-0">
                        <div class="tab-content p-0">
                            <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                                <div id="incomeChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 order-1 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Grafik Siswa Tidak Hadir Dalam Pembelajaran</h5>
                            <small class="text-muted">(Sakit,Izin,Alfa,Dinas Dalam, Dinas Luar)</small>
                        </div>
                    </div>
                    <div class="card-body px-0">
                        <div class="tab-content p-0">
                            <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                                <div id="incomeChart2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <!-- / Content -->
@endsection

@section('script')
    <script src="{{ url('') }}/assets/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ url('') }}/assets/assets/js/dashboards-analytics.js"></script>
@endsection
