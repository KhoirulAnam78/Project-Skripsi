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
                        <div class="col-sm-8">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Selamat Datang Bapak/Ibu Wali Murid Dari
                                    {{ Auth::user()->siswa->nama }}</h5>
                                <p class="mb-4">
                                    Sistem infromasi monitoring merupakan sistem yang bertujuan untuk memantau kegiatan
                                    pembelajaran yang dilakukan siswa di SMAN Titian Teras H. Abdurrahman Sayoeti Jambi
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-4 text-center text-sm-left">
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
            <!-- Total Revenue -->
            <div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2 mb-4">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-md-12">
                            <h5 class="card-header m-0 me-2 pb-3 d-inline-block">Jadwal Kegiatan Siswa Hari
                                Ini ({{ \Carbon\Carbon::now()->translatedFormat('l, d-m-Y') }})</h5>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                    <tr class="table-primary">
                                        <th>Jam</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Guru</th>
                                        <th>Topik</th>
                                        <th>Presensi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($jadwal as $j)
                                        <tr class="table-default">
                                            <td>{{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }}
                                            </td>
                                            <td>{{ $j->mataPelajaran->nama }}</td>
                                            <td>{{ $j->guru->nama }}</td>
                                            <td style="white-space: normal">
                                                @if (count($j->monitoringPembelajarans->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))) !== 0)
                                                    {{ $j->monitoringPembelajarans->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))->first()->topik }}
                                                @else
                                                    {{ '' }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (count($j->monitoringPembelajarans->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))) !== 0)
                                                    @php
                                                        $get = $j->monitoringPembelajarans->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))->first()->kehadiranPembelajarans;
                                                        $kehadiran = $get->where('siswa_id', Auth::user()->siswa->id);
                                                    @endphp
                                                    {{ $kehadiran->first()->status }}
                                                @else
                                                    {{ '' }}
                                                @endif
                                            </td>
                                            @php
                                                // dd();
                                                if (count($j->monitoringPembelajarans->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))) !== 0) {
                                                    if (\Carbon\Carbon::now()->translatedFormat('H.i') > $j->waktu_berakhir) {
                                                        $status = 'Telah Berakhir';
                                                    } elseif (\Carbon\Carbon::now()->translatedFormat('H.i') < $j->waktu_mulai) {
                                                        $status = 'Belum Dimulai';
                                                    } else {
                                                        $status = 'Sedang Berlangsung';
                                                    }
                                                } else {
                                                    $status = 'Tidak Terlaksana';
                                                }
                                            @endphp
                                            <td
                                                class="
                                                @php
if ($status === 'Telah Berakhir'){
                                                        echo 'badge bg-label-info my-1';
                                                    } else if($status === 'Belum Dimulai'){
                                                    echo 'badge bg-label-warning my-1';
                                                    }else if ($status === 'Sedang Berlangsung'){
                                                        echo'badge bg-label-success my-1';
                                                    } else {
                                                        echo'badge bg-label-danger my-1';
                                                    } @endphp">
                                                {{ $status }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Total Revenue -->
        </div>

    </div>
    <!-- / Content -->
@endsection
