<div>
    <div class="card-b">
        <div class="row mx-2 mb-3">
            <div class="col-lg-3 col-md-3">
                <label for="Kegiatan" class="form-label">Kegiatan</label>
                <select wire:model="filterKegiatan" id="Kegiatan" class="form-select">
                    <option value="pembelajaran">Pembelajaran</option>

                </select>
            </div>
            <div class="col-lg-3 col-md-3">
                <label for="Kelas" class="form-label">Kelas</label>
                <select wire:model="filterKelas" id="Kelas" class="form-select">
                    @if ($kelas)
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    @else
                        <option value="">Tidak ada kelas</option>

                    @endif
                </select>
            </div>
        </div>
        <div class="row mx-3">
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
                                            if (\Carbon\Carbon::now()->translatedFormat('H.i') > $j->waktu_berakhir) {
                                                $status = 'Telah Berakhir';
                                            } else {
                                                $status = 'Sedang Berlangsung';
                                            }
                                        } elseif (\Carbon\Carbon::now()->translatedFormat('H.i') < $j->waktu_mulai) {
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
                                    {{-- @php
                                        
                                        dd($j);
                                    @endphp --}}
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
                                            if (\Carbon\Carbon::now()->translatedFormat('H.i') > $j->waktu_berakhir) {
                                                $status = 'Telah Berakhir';
                                            } else {
                                                $status = 'Sedang Berlangsung';
                                            }
                                        } elseif (\Carbon\Carbon::now()->translatedFormat('H.i') < $j->waktu_mulai) {
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
                                    {{-- @php
                        
                        dd($j);
                    @endphp --}}
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
                {{-- @if (count($dataSiswa) !== 0)
              {{ $dataSiswa->links() }}
          @endif --}}
            </div>
        </div>
    </div>
</div>
