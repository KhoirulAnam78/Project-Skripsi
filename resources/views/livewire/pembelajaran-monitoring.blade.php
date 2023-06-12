<div>
    {{-- <div>
        <h2>Currrent Time : {{ now() }}</h2>
    </div> --}}
    <div class="row  mt-3">
        <div class="col-lg-9 col-xl-9 col-md-6 col-sm-6">
            <div class="row">
                @if (count($jadwalPengganti) !== 0)
                    <h5>Jadwal Pengganti</h5>
                    @foreach ($jadwalPengganti as $j)
                        {{-- PRESENSI --}}
                        @php
                            if (count($j->jadwalPelajaran->monitoringPembelajarans->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))) !== 0) {
                                $presensi = $j->jadwalPelajaran->monitoringPembelajarans->first()->kehadiranPembelajarans;
                            } else {
                                $presensi = null;
                            }
                        @endphp

                        {{-- Status --}}
                        @php
                            // dd();
                            if (count($j->jadwalPelajaran->monitoringPembelajarans->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))) !== 0) {
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
                        @endphp
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xl-4">
                            <div class="card mb-3" style="background-color: rgb(243, 243, 243)">
                                {{-- <div
                                    class="card-header fw-bold ">
                                    {{ $status }} </div> --}}
                                <span
                                    class="badge {{ $status == 'Telah Berakhir' ? 'bg-label-primary' : '' }} {{ $status == 'Tidak Terlaksana' ? 'bg-label-danger' : '' }} {{ $status == 'Sedang Berlangsung' ? 'bg-label-success' : '' }} {{ $status == 'Belum Dimulai' ? 'bg-label-warning' : '' }}"
                                    style="font-size: 16px">{{ $status }}</span>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $j->jadwalPelajaran->kelas->nama }}</h5>
                                    <p class="card-text">Jam :
                                        {{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }}
                                        <br>
                                        Pelajaran : {{ $j->jadwalPelajaran->mataPelajaran->nama }} <br>
                                        Guru : {{ $j->jadwalPelajaran->guru->nama }} <br>
                                        Materi :
                                        {{ count($j->jadwalPelajaran->monitoringPembelajarans) === 0 ? '-' : $j->jadwalPelajaran->monitoringPembelajarans->first()->topik }}
                                        <br>
                                        H :
                                        {{ $presensi === null ? '0' : $presensi->where('status', 'hadir')->count() }} |
                                        I :
                                        {{ $presensi === null ? '0' : $presensi->where('status', 'izin')->count() }} |
                                        A :
                                        {{ $presensi === null ? '0' : $presensi->where('status', 'alfa')->count() }} |
                                        <br>
                                        S :
                                        {{ $presensi === null ? '0' : $presensi->where('status', 'sakit')->count() }} |
                                        DD :
                                        {{ $presensi === null ? '0' : $presensi->where('status', 'dinas dalam')->count() }}
                                        |
                                        DL :
                                        {{ $presensi === null ? '0' : $presensi->where('status', 'dinas luar')->count() }}
                                        |
                                    </p>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="row">
                @if (count($jadwal) !== 0)
                    <h5>Jadwal Normal</h5>
                    @foreach ($jadwal as $j)
                        @php
                            if (count($j->monitoringPembelajarans) !== 0) {
                                $presensi = $j->monitoringPembelajarans->first()->kehadiranPembelajarans;
                            } else {
                                $presensi = null;
                            }
                        @endphp
                        @php
                            // dd();
                            if (count($j->monitoringPembelajarans->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))) !== 0) {
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
                        @endphp
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xl-4">
                            <div class="card mb-3" style="background-color: rgb(243, 243, 243)">
                                <span
                                    class="badge {{ $status == 'Telah Berakhir' ? 'bg-label-primary' : '' }} {{ $status == 'Tidak Terlaksana' ? 'bg-label-danger' : '' }} {{ $status == 'Sedang Berlangsung' ? 'bg-label-success' : '' }} {{ $status == 'Belum Dimulai' ? 'bg-label-warning' : '' }}"
                                    style="font-size: 16px">{{ $status }}</span>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $j->kelas->nama }}</h5>
                                    <p class="card-text">Jam :
                                        {{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }}
                                        <br>
                                        Pelajaran : {{ $j->mataPelajaran->nama }} <br>
                                        Guru : {{ $j->guru->nama }} <br>
                                        Materi :
                                        {{ count($j->monitoringPembelajarans) === 0 ? '-' : $j->monitoringPembelajarans->first()->topik }}
                                        <br>
                                        H :
                                        {{ $presensi === null ? '0' : $presensi->where('status', 'hadir')->count() }} |
                                        I :
                                        {{ $presensi === null ? '0' : $presensi->where('status', 'izin')->count() }} |
                                        A :
                                        {{ $presensi === null ? '0' : $presensi->where('status', 'alfa')->count() }} |
                                        <br>
                                        S :
                                        {{ $presensi === null ? '0' : $presensi->where('status', 'sakit')->count() }} |
                                        DD :
                                        {{ $presensi === null ? '0' : $presensi->where('status', 'dinas dalam')->count() }}
                                        |
                                        DL :
                                        {{ $presensi === null ? '0' : $presensi->where('status', 'dinas luar')->count() }}
                                        |
                                    </p>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="col-lg-3 col-xl-3 col-md-4 col-sm-6">
            <div class="card bg-secondary text-white mb-3 mt-4">
                <div class="card-header fw-bold">Siswa Tidak Hadir Dalam Pembelajaran</div>
                <div class="card-body">
                    @if (count($tidakHadir) !== 0)
                        @foreach ($tidakHadir as $p)
                            <p class="card-text fw-bold">
                                {{ $p->jadwalPelajaran->kelas->nama }} :
                                {{ $p->jadwalPelajaran->mataPelajaran->nama }} <br>

                            </p>
                            <p>
                                @php
                                    $a = 0;
                                @endphp
                                @foreach ($p->kehadiranPembelajarans as $kehadiran)
                                    {{ ++$a . '. ' . $kehadiran->siswa->nama . ' (' . ucwords($kehadiran->status) . ')' }}
                                    <br>
                                @endforeach
                            </p>
                        @endforeach
                    @else
                        <p class="card-text fw-bold">
                            --Tidak ada data--

                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- 
    <div class="table-responsive text-nowrap mb-3">
        <table class="table table-striped align-top" id="example">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th>Guru</th>
                    <th>Mata Pelajaran</th>
                    <th>Topik</th>
                    <th>Presensi</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @if (count($jadwal) !== 0)
                    @php
                        $a = 0;
                    @endphp
                    @foreach ($jadwal as $j)
                        <tr>
                            @php
                                // dd();
                                if (count($j->monitoringPembelajarans->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))) !== 0) {
                                    if (\Carbon\Carbon::now()->translatedFormat('H:i') > substr($j->waktu_berakhir, 0, -3)) {
                                        $status = 'Telah Berakhir';
                                    } else {
                                        $status = 'Sedang Berlangsung';
                                    }
                                } elseif (\Carbon\Carbon::now()->translatedFormat('H:i') < substr($j->waktu_mulai, 0, -3)) {
                                    $status = 'Belum Dimulai';
                                } else {
                                    $status = 'Tidak Terlaksana';
                                }
                            @endphp
                            <td>
                                <span
                                    class="
                                        @php
if ($status === 'Telah Berakhir'){
                                                echo 'badge bg-label-info ';
                                            } else if($status === 'Belum Dimulai'){
                                            echo 'badge bg-label-warning ';
                                            }else if ($status === 'Sedang Berlangsung'){
                                                echo'badge bg-label-success ';
                                            } else {
                                                echo'badge bg-label-danger ';
                                            } @endphp">{{ $status }}</span>
                            </td>
                            <td>{{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }}
                            </td>
                            <td>{{ $j->guru->nama }}</td>
                            <td>{{ $j->mataPelajaran->nama }}</td>
                            <td style="white-space: normal">
                                {{ count($j->monitoringPembelajarans) === 0 ? '' : $j->monitoringPembelajarans->first()->topik }}
                            </td>
                            @php
                                if (count($j->monitoringPembelajarans) !== 0) {
                                    $presensi = $j->monitoringPembelajarans->first()->kehadiranPembelajarans;
                                } else {
                                    $presensi = null;
                                }
                                
                            @endphp
                            <td>Hadir :
                                {{ $presensi === null ? '0' : $presensi->where('status', 'hadir')->count() }}
                                <br> Izin :
                                {{ $presensi === null ? '0' : $presensi->where('status', 'izin')->count() }}
                                <br> Alfa :
                                {{ $presensi === null ? '0' : $presensi->where('status', 'alfa')->count() }}
                                <br> Sakit :
                                {{ $presensi === null ? '0' : $presensi->where('status', 'sakit')->count() }}
                                <br> DD :
                                {{ $presensi === null ? '0' : $presensi->where('status', 'dinas dalam')->count() }}
                                <br> DL :
                                {{ $presensi === null ? '0' : $presensi->where('status', 'dinas luar')->count() }}
                            </td>
                            <td>{{ count($j->monitoringPembelajarans) === 0 ? '' : $j->monitoringPembelajarans->first()->keterangan }}
                            </td>
                        </tr>
                    @endforeach
                @endif
                @if (count($jadwalPengganti) !== 0)
                    @foreach ($jadwalPengganti as $j)
                        <tr>
                            @php
                                // dd();
                                if (count($j->jadwalPelajaran->monitoringPembelajarans->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))) !== 0) {
                                    if (\Carbon\Carbon::now()->translatedFormat('H:i') > substr($j->waktu_berakhir, 0, -3)) {
                                        $status = 'Telah Berakhir';
                                    } else {
                                        $status = 'Sedang Berlangsung';
                                    }
                                } elseif (\Carbon\Carbon::now()->translatedFormat('H:i') < substr($j->waktu_mulai, 0, -3)) {
                                    $status = 'Belum Dimulai';
                                } else {
                                    $status = 'Tidak Terlaksana';
                                }
                            @endphp
                            <td>
                                <span
                                    class="
                        @php
if ($status === 'Telah Berakhir'){
                                echo 'badge bg-label-info ';
                            } else if($status === 'Belum Dimulai'){
                            echo 'badge bg-label-warning ';
                            }else if ($status === 'Sedang Berlangsung'){
                                echo'badge bg-label-success ';
                            } else {
                                echo'badge bg-label-danger ';
                            } @endphp">{{ $status }}</span>
                            </td>
                            <td>{{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }}
                            </td>
                            @php
                                
                                dd($j);
                            @endphp
                            <td>{{ $j->jadwalPelajaran->guru->nama }}</td>
                            <td>{{ $j->jadwalPelajaran->mataPelajaran->nama }}</td>
                            <td style="white-space: normal">
                                {{ count($j->jadwalPelajaran->monitoringPembelajarans) === 0 ? '' : $j->jadwalPelajaran->monitoringPembelajarans->first()->topik }}
                            </td>
                            @php
                                if (count($j->jadwalPelajaran->monitoringPembelajarans) !== 0) {
                                    $presensi = $j->jadwalPelajaran->monitoringPembelajarans->first()->kehadiranPembelajarans;
                                } else {
                                    $presensi = null;
                                }
                                
                            @endphp
                            <td>Hadir :
                                {{ $presensi === null ? '0' : $presensi->where('status', 'hadir')->count() }}
                                <br> Izin :
                                {{ $presensi === null ? '0' : $presensi->where('status', 'izin')->count() }}
                                <br> Alfa :
                                {{ $presensi === null ? '0' : $presensi->where('status', 'alfa')->count() }}
                                <br> Sakit :
                                {{ $presensi === null ? '0' : $presensi->where('status', 'sakit')->count() }}
                                <br> DD :
                                {{ $presensi === null ? '0' : $presensi->where('status', 'dinas dalam')->count() }}
                                <br> DL :
                                {{ $presensi === null ? '0' : $presensi->where('status', 'dinas luar')->count() }}
                            </td>
                            <td>{{ count($j->jadwalPelajaran->monitoringPembelajarans) === 0 ? '' : $j->jadwalPelajaran->monitoringPembelajarans->first()->keterangan }}
                            </td>
                        </tr>
                    @endforeach
                @endif
                @if (count($jadwal) === 0 and count($jadwalPengganti) === 0)
                    <tr>
                        <td colspan='9' align="center"><span>Tidak ada data</span></td>
                    </tr>
                @endif

            </tbody>

        </table>
    </div> --}}
</div>
