<div wire:poll.20s>
    <div class="d-flex justify-content-end">
        <h5>Waktu saat ini : {{ \Carbon\Carbon::now()->translatedFormat('H:i:s') }}</h5>
    </div>
    <div class="row mt-3">
        <div class="col-lg-9 col-xl-9 col-md-9 col-sm-6">
            <div class="row">
                @if (count($jadwal) !== 0)
                    <h5>Jadwal Kegiatan</h5>
                    @foreach ($jadwal as $j)
                        @php
                            if ($j->kegiatan->narasumber == 0) {
                                if (count($j->monitoringKegiatan) !== 0) {
                                    $presensi = $j->monitoringKegiatan->first()->kehadiranKegiatan;
                                } else {
                                    $presensi = null;
                                }
                                if (count($j->monitoringKegiatan->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))) !== 0) {
                                    if (\Carbon\Carbon::now()->translatedFormat('H:i') > substr($j->waktu_berakhir, 0, -3)) {
                                        $status = 'Telah Berakhir';
                                    } else {
                                        $status = 'Sedang Berlangsung';
                                    }
                                } elseif (\Carbon\Carbon::now()->translatedFormat('H:i') < substr($j->waktu_mulai, 0, -3) or \Carbon\Carbon::now()->translatedFormat('H:i') < substr($j->waktu_berakhir, 0, -3)) {
                                    $status = 'Belum Dimulai';
                                } else {
                                    $status = 'Tidak Terlaksana';
                                }
                            } else {
                                if (count($j->monitoringKegnas) !== 0) {
                                    $presensi = $j->monitoringKegnas->first()->kehadiranKegnas;
                                } else {
                                    $presensi = null;
                                }
                            
                                if (count($j->monitoringKegnas->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))) !== 0) {
                                    if (\Carbon\Carbon::now()->translatedFormat('H:i') > substr($j->waktu_berakhir, 0, -3)) {
                                        $status = 'Telah Berakhir';
                                    } else {
                                        $status = 'Sedang Berlangsung';
                                    }
                                } elseif (\Carbon\Carbon::now()->translatedFormat('H:i') < substr($j->waktu_mulai, 0, -3) or \Carbon\Carbon::now()->translatedFormat('H:i') < substr($j->waktu_berakhir, 0, -3)) {
                                    $status = 'Belum Dimulai';
                                } else {
                                    $status = 'Tidak Terlaksana';
                                }
                            }
                        @endphp
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xl-4 col-xxl-3">
                            <div class="card mb-3" style="background-color: rgb(243, 243, 243)">
                                <span
                                    class="badge {{ $status == 'Telah Berakhir' ? 'bg-label-primary' : '' }} {{ $status == 'Tidak Terlaksana' ? 'bg-label-danger' : '' }} {{ $status == 'Sedang Berlangsung' ? 'bg-label-success' : '' }} {{ $status == 'Belum Dimulai' ? 'bg-label-warning' : '' }}"
                                    style="font-size: 16px">{{ $status }}</span>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $j->kegiatan->nama }}</h5>
                                    <p class="card-text">Jam :
                                        {{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }}
                                        <br>
                                        Angkatan : {{ $j->angkatan->nama }} <br>
                                        @if ($j->kegiatan->narasumber == 1)
                                            Narasumber :
                                            {{ count($j->monitoringKegnas) == 0 ? '-' : $j->monitoringKegnas->first()->narasumber->nama }}
                                            <br>
                                            Materi :
                                            {{ count($j->monitoringKegnas) === 0 ? '-' : $j->monitoringKegnas->first()->topik }}
                                            <br>
                                        @endif
                                    </p>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr class="table-dark">
                                                    <td>H</td>
                                                    <td>I</td>
                                                    <td>A</td>
                                                    <td>S</td>
                                                    <td>DD</td>
                                                    <td>DL</td>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td>{{ $presensi === null ? '0' : $presensi->where('status', 'hadir')->count() }}
                                                    </td>
                                                    <td>{{ $presensi === null ? '0' : $presensi->where('status', 'izin')->count() }}
                                                    </td>
                                                    <td>
                                                        {{ $presensi === null ? '0' : $presensi->where('status', 'alfa')->count() }}
                                                    </td>
                                                    <td>{{ $presensi === null ? '0' : $presensi->where('status', 'sakit')->count() }}
                                                    </td>
                                                    <td>{{ $presensi === null ? '0' : $presensi->where('status', 'dinas dalam')->count() }}
                                                    </td>
                                                    <td>{{ $presensi === null ? '0' : $presensi->where('status', 'dinas luar')->count() }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="col-lg-3 col-xl-3 col-md-3 col-sm-6">
            <h5 class="text-white">Presensi</h5>
            <div class="card bg-secondary text-white mb-3">
                <div class="card-header fw-bold">Siswa Tidak Hadir Dalam Kegiatan</div>
                <div class="card-body">
                    @if (count($tidakHadirKegiatan) !== 0)
                        @foreach ($tidakHadirKegiatan as $p)
                            <p class="card-text fw-bold">
                                {{ $p->jadwalKegiatan->kegiatan->nama }} (
                                Angkatan {{ $p->jadwalKegiatan->angkatan->nama }} )<br>
                            </p>
                            <p>
                                @php
                                    $a = 0;
                                @endphp
                                @foreach ($p->kehadiranKegiatan as $kehadiran)
                                    {{ ++$a . '. ' . $kehadiran->siswa->nama . ' (' . ucwords($kehadiran->status) . ')' }}
                                    <br>
                                @endforeach
                            </p>
                        @endforeach
                        @if (count($tidakHadirKegnas) !== 0)
                            @foreach ($tidakHadirKegnas as $p)
                                <p class="card-text fw-bold">
                                    {{ $p->jadwalKegiatan->kegiatan->nama }} (
                                    Angkatan {{ $p->jadwalKegiatan->angkatan->nama }} )<br>
                                </p>
                                <p>
                                    @php
                                        $a = 0;
                                    @endphp
                                    @foreach ($p->kehadiranKegnas as $kehadiran)
                                        {{ ++$a . '. ' . $kehadiran->siswa->nama . ' (' . ucwords($kehadiran->status) . ')' }}
                                        <br>
                                    @endforeach
                                </p>
                            @endforeach
                        @endif
                    @else
                        <p class="card-text fw-bold">
                            --Tidak ada data--

                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>


</div>
