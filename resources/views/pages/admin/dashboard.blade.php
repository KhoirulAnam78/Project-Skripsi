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
                                <img src="{{ url('') }}/assets/assets/img/icons/unicons/chart-success.png"
                                    alt="chart success" class="rounded" />
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
                                <img src="{{ url('') }}/assets/assets/img/icons/unicons/wallet-info.png"
                                    alt="Credit Card" class="rounded" />
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
                                <img src="{{ url('') }}/assets/assets/img/icons/unicons/paypal.png" alt="Credit Card"
                                    class="rounded" />
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-2">Kelas Aktif</span>
                        <h3 class="card-title text-nowrap mb-2">{{ $kelasAktif }}</h3>
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-3 col-md-3 col-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ url('') }}/assets/assets/img/icons/unicons/cc-primary.png"
                                    alt="Credit Card" class="rounded" />
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Transactions</span>
                        <h3 class="card-title mb-2">$14,857</h3>
                        <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.14%</small>
                    </div>
                </div>
            </div> --}}
        </div>

        <div class="row">


            <!-- Total Revenue -->
            {{-- <div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2 mb-4">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-md-12">
                            <h5 class="card-header m-0 me-2 pb-3 d-inline-block">Jadwal Mengajar Hari
                                Ini ({{ \Carbon\Carbon::now()->translatedFormat('l, d-m-Y') }})</h5>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                    <tr class="table-primary">
                                        <th>Jam</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Total Revenue -->
            {{-- <div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2 mb-4">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-md-8">
                            <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>
                            <div id="totalRevenueChart" class="px-2"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                            id="growthReportId" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            2022
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                                            <a class="dropdown-item" href="javascript:void(0);">2021</a>
                                            <a class="dropdown-item" href="javascript:void(0);">2020</a>
                                            <a class="dropdown-item" href="javascript:void(0);">2019</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="growthChart"></div>
                            <div class="text-center fw-semibold pt-3 mb-2">62% Company Growth</div>

                            <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                                <div class="d-flex">
                                    <div class="me-2">
                                        <span class="badge bg-label-primary p-2"><i
                                                class="bx bx-dollar text-primary"></i></span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>2022</small>
                                        <h6 class="mb-0">$32.5k</h6>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="me-2">
                                        <span class="badge bg-label-info p-2"><i class="bx bx-wallet text-info"></i></span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>2021</small>
                                        <h6 class="mb-0">$41.2k</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <!--/ Total Revenue -->
        </div>
        <livewire:persentase-dashboard />
        <div class="row">
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
                            <h5 class="m-0 me-2">Grafik Siswa Tidak Hadir</h5>
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
        </div>
    </div>
    <!-- / Content -->
@endsection

@section('script')
    <script src="{{ url('') }}/assets/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ url('') }}/assets/assets/js/dashboards-analytics.js"></script>
@endsection
