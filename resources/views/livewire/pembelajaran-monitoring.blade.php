<div wire:poll.20s>
    <div class="row">
        <div class="col-lg-3 col-md-3 mt-3">
            <label for="Kegiatan" class="form-label">Tampilan Monitoring</label>
            <select wire:model="filterTampilan" id="tampilan" class="form-select">
                <option value="semua">Semua</option>
                <option value="rentang">Waktu saat ini</option>
            </select>
        </div>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <h5>Waktu saat ini : {{ \Carbon\Carbon::now()->translatedFormat('H:i:s') }}</h5>
    </div>
    <div class="row  my-3">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="row">
                {{-- @if (count($jadwalPengganti) !== 0)
                    <h5>Jadwal Pengganti</h5>
                    @foreach ($jadwalPengganti as $j)
                        {{-- PRESENSI
                @php
                    if (count($j->jadwalPelajaran->monitoringPembelajarans->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))) !== 0) {
                        $presensi = $j->jadwalPelajaran->monitoringPembelajarans->first()->kehadiranPembelajarans;
                    } else {
                        $presensi = null;
                    }
                @endphp

                {{-- Status
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
                <div class="col-lg-4 col-md-4 col-sm-6 col-xl-4 col-xxl-3">
                    <div class="card mb-3" style="background-color: rgb(243, 243, 243)">
                        {{-- <div
                                    class="card-header fw-bold ">
                                    {{ $status }} </div>
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
                @endif --}}
            </div>
            <div class="row">
                @if (count($jadwal) !== 0)
                    <h5>Jadwal Pelajaran</h5>
                    @foreach ($jadwal as $j)
                        {{-- @php
                            if (count($j->monitoringPembelajarans) !== 0) {
                                $presensi = $j->monitoringPembelajarans->first()->kehadiranPembelajarans;
                            } else {
                                $presensi = null;
                            }
                        @endphp --}}
                        @php
                            // dd();
                            if ($j->monitoring_pembelajaran_id) {
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
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xl-4 col-xxl-3">
                            <div class="card mb-3" style="background-color: rgb(243, 243, 243)">
                                <span
                                    class="badge {{ $status == 'Telah Berakhir' ? 'bg-label-primary' : '' }} {{ $status == 'Tidak Terlaksana' ? 'bg-label-danger' : '' }} {{ $status == 'Sedang Berlangsung' ? 'bg-label-success' : '' }} {{ $status == 'Belum Dimulai' ? 'bg-label-warning' : '' }}"
                                    style="font-size: 16px">{{ $status }}</span>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $j->kelas }}</h5>
                                    <p class="card-text">Jam :
                                        {{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }}
                                        <br>
                                        Pelajaran : {{ $j->mata_pelajaran }} <br>
                                        Guru : {{ $j->guru }} <br>
                                        Materi :
                                        {{ $j->monitoring_pembelajaran_id == null ? '-' : $j->topik }}
                                        <br>
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
                                                    <td>{{ $j->jml_hadir }}
                                                    </td>
                                                    <td>{{ $j->jml_izin }}
                                                    </td>
                                                    <td>{{ $j->jml_alfa }}
                                                    </td>
                                                    <td>{{ $j->jml_sakit }}
                                                    </td>
                                                    <td>{{ $j->jml_dd }}
                                                    </td>
                                                    <td>{{ $j->jml_dl }}
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
            @if (count($jadwal) === 0)
                <div class="row">
                    <center>
                        <h5>Tidak Ada Monitoring</h5>
                    </center>
                </div>
            @endif
            <div class="row">
                @if (count($jadwal) !== 0)
                    {{ $jadwal->links() }}
                @endif
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <h5 class="text-white">Presensi</h5>
            <div class="card bg-secondary text-white mb-3">
                <div class="card-header fw-bold">Siswa Tidak Hadir Dalam Pembelajaran</div>
                <div class="card-body">
                    <div class="row">
                        @if (count($tidakHadir) !== 0)
                            @foreach ($tidakHadir as $p)
                                <div class="col-3">
                                    <div class="card text-dark mt-3">
                                        <div class="card-header">
                                            <p class="card-text fw-bold">
                                                {{ $p->kelas }} :
                                                {{ $p->mata_pelajaran }} <br>
                                            </p>
                                        </div>
                                        <div class="card-body">
                                            <p>
                                                {{ $p->nama_siswa }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
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
    </div>

</div>
