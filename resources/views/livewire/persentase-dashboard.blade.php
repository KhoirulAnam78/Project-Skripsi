<div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12 order-0 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Filter</h5>
                        <small class="text-muted"></small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        {{-- <div class="col-lg-4 col-md-4">
                            <label for="filterTahun" class="form-label">Tahun</label>
                            <select wire:model="filterTahun" id="filterTahun" class="form-select">
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                            </select>
                        </div> --}}
                        <input type="hidden" id="pembelajaranPerbulan"
                            value="{{ json_encode($grafikJmlPembelajaran) }}">
                        <input type="hidden" id="kehadiranPerbulan" value="{{ json_encode($grafikJmlTidakHadir) }}">
                        <input type="hidden" id="jmlPembelajaran" value="{{ $pembelajaran->total }}">
                        <input type="hidden" id="terlaksana" value="{{ $pembelajaran->terlaksana }}">
                        <input type="hidden" id="tidakTerlaksana" value="{{ $pembelajaran->tidak_terlaksana }}">
                        <div class="col-lg-4 col-md-4">
                            <label for="filterBulan" class="form-label">Bulan</label>
                            <select wire:model="filterBulan" id="filterBulan" class="form-select">
                                @foreach ($bulan as $b)
                                    <option value="{{ $b['tanggal'] }}">{{ $b['bulan'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-lg-4 col-md-4">
                            <label for="minggu" class="form-label">Minggu</label>
                            <select wire:model="filterMinggu" id="minggu" class="form-select">
                                <option value="">Semua</option>
                                <option value="Pertama">Pertama</option>
                                <option value="Kedua">Kedua</option>
                                <option value="Ketiga">Ketiga</option>
                                <option value="Keempat">Keempat</option>
                            </select>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Order Statistics -->
        <div class="col-sm-12 col-md-7 col-lg-7 col-xl-7 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Persentase Pembelajaran</h5>
                        <small class="text-muted"></small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex flex-column align-items-center gap-1">
                            <h2 class="mb-2">{{ $pembelajaran->total }}</h2>
                            <span>Total Pembelajaran</span>
                        </div>
                        <div id="orderStatisticsChart"></div>
                    </div>
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-2 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success"><i class='bx bx-check'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Terlaksana</h6>
                                    <small class="text-muted"></small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $pembelajaran->terlaksana }}</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-2 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-x"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Tidak Terlaksana</h6>
                                    <small class="text-muted"></small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $pembelajaran->tidak_terlaksana }}</small>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Order Statistics -->

        <!-- Transactions -->
        <div class="col-sm-12 col-md-5 col-lg-5 order-2 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Jumlah Ketidakhadiran Pembelajaran</h5>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <li class="d-flex pb-2">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-info"><i class='bx bx-group'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Izin</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $presensi[0]['izin'] }}</h6>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex pb-2">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-group'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Sakit</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $presensi[0]['sakit'] }}</h6>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex pb-2">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-danger"><i class='bx bx-group'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Alfa</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $presensi[0]['alfa'] }}</h6>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex pb-2">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success"><i
                                        class='bx bx-group'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Dinas Dalam</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $presensi[0]['dd'] }}</h6>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex pb-2">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary"><i
                                        class='bx bx-group'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Dinas Luar</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $presensi[0]['dl'] }}</h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Transactions -->


        <!-- Transactions -->
        {{-- <div class="col-sm-12 col-md-6 col-lg-6 order-2 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Jumlah Pelaksanaan Kegiatan</h5>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @foreach ($jumlahKegiatan as $j)
                            <li class="d-flex pb-2">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class='bx bxs-time-five'></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">{{ $j['kegiatan'] }}</h6>
                                    </div>
                                    <div class="user-progress d-flex align-items-center gap-1">
                                        <h6 class="mb-0">{{ $j['jml'] }}</h6>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 order-2 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Jumlah Ketidakhadiran Kegiatan</h5>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <li class="d-flex pb-2">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-info"><i class='bx bx-group'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Izin</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $presensiKegiatan[0]['izin'] }}</h6>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex pb-2">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-warning"><i
                                        class='bx bx-group'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Sakit</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $presensiKegiatan[0]['sakit'] }}</h6>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex pb-2">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-danger"><i
                                        class='bx bx-group'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Alfa</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $presensiKegiatan[0]['alfa'] }}</h6>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex pb-2">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success"><i
                                        class='bx bx-group'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Dinas Dalam</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $presensiKegiatan[0]['dd'] }}</h6>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex pb-2">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary"><i
                                        class='bx bx-group'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Dinas Luar</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $presensiKegiatan[0]['dl'] }}</h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div> --}}

    </div>

    {{-- <script>
        var data = JSON.parse(document.getElementById("pembelajaranPerbulan").value);
        console.log('Hallo');
        console.log(data);
    </script> --}}

</div>
