<table class="table table-striped align-top" id="example">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kode</th>
            <th>Mata Pelajaran</th>
            <th>Jam Wajib</th>
            <th>Tidak Terlaksana</th>
            <th>% Terlaksana</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">
        @if (count($guru) === 0)
            <tr>
                <td colspan='9' align="center"><span>Tidak ada data</span></td>
            </tr>
        @else
            @foreach ($guru as $g)
                @if (count($g->jadwalPelajarans->groupBy('mata_pelajaran_id')) !== 0)
                    @php
                        $b = $g->jadwalPelajarans->groupBy('mata_pelajaran_id')->first();
                        $rowCount = count($g->jadwalPelajarans->groupBy('mata_pelajaran_id'));
                    @endphp
                @else
                    @php
                        $b = null;
                        $rowCount = 1;
                    @endphp
                @endif
                <tr>
                    <td
                        {{ count($g->jadwalPelajarans->groupBy('mata_pelajaran_id')) !== 1 ? 'rowspan=' . $rowCount : '' }}>
                        {{ ($guru->currentpage() - 1) * $guru->perpage() + $loop->index + 1 }}
                    </td>
                    <td
                        {{ count($g->jadwalPelajarans->groupBy('mata_pelajaran_id')) !== 1 ? 'rowspan=' . $rowCount : '' }}>
                        {{ $g->nama }}</td>
                    <td
                        {{ count($g->jadwalPelajarans->groupBy('mata_pelajaran_id')) !== 1 ? 'rowspan=' . $rowCount : '' }}>
                        {{ $g->kode_guru }}</td>
                    <td>{{ $b !== null ? $b->first()->mataPelajaran->nama : '' }}</td>
                    @php
                        $diff = 0;
                        if ($b !== null) {
                            foreach ($b as $j) {
                                $datetime1 = strtotime($j->waktu_mulai);
                                $datetime2 = strtotime($j->waktu_berakhir);
                                $interval = abs($datetime2 - $datetime1);
                                $minutes = round($interval / 60);
                                // dd($minutes);
                                $perbedaan = floor($minutes / 35);
                                $diff = $diff + $perbedaan;
                                // dd(floor($diff));
                            }
                        }
                    @endphp
                    <td align="center">{{ $diff }}</td>

                    @php
                        $jml = 0;
                        $keterangan = [];
                        $tgl = [];
                        
                        if ($b !== null) {
                            foreach ($b as $j) {
                                if (count($j->monitoringPembelajarans) !== 0) {
                                    foreach ($j->monitoringPembelajarans as $m) {
                                        array_push($tgl, $m->tanggal);
                                        array_push($keterangan, $m->keterangan);
                                        // dd($keterangan);
                                        if ($m->status_validasi === 'tidak valid') {
                                            $datetime1 = strtotime($j->waktu_mulai);
                                            $datetime2 = strtotime($j->waktu_berakhir);
                                            $interval = abs($datetime2 - $datetime1);
                                            $minutes = round($interval / 60);
                                            // dd($minutes);
                                            $perbedaan = floor($minutes / 35);
                        
                                            // $date1 = (float) substr($m->waktu_mulai, 0, -3);
                                            // $date2 = (float) substr($m->waktu_berakhir, 0, -3);
                                            $jml = $jml + $perbedaan;
                                        }
                                    }
                                }
                            }
                        }
                    @endphp
                    <td align="center">{{ $jml === 0 ? '0' : $jml }}
                    </td>
                    <td align="center">
                        @if ($jml === 0)
                            {{ '100%' }}
                        @else
                            @php
                                $data1 = $jml;
                                $data2 = $diff;
                                $total = round((($data2 - $data1) / $data2) * 100);
                            @endphp
                            {{ $total . '%' }}
                        @endif
                    </td>
                    <td>
                        @foreach ($keterangan as $key => $k)
                            @if ($k !== null)
                                {{ $tgl[$key] . ' : ' . $k }}
                                <br>
                            @endif
                        @endforeach
                    </td>
                </tr>
                @foreach ($g->jadwalPelajarans->groupBy('mata_pelajaran_id') as $key => $b)
                    @if ($loop->first)
                        @continue
                    @endif
                    <tr>
                        <td>{{ $b !== null ? $b->first()->mataPelajaran->nama : '' }}</td>
                        @php
                            $diff = 0;
                            if ($b !== null) {
                                foreach ($b as $j) {
                                    $datetime1 = strtotime($j->waktu_mulai);
                                    $datetime2 = strtotime($j->waktu_berakhir);
                                    $interval = abs($datetime2 - $datetime1);
                                    $minutes = round($interval / 60);
                                    // dd($minutes);
                                    $perbedaan = floor($minutes / 35);
                                    $diff = $diff + $perbedaan;
                                }
                            }
                        @endphp
                        <td align="center">{{ $diff }}</td>

                        @php
                            $jml = 0;
                            $keterangan = [];
                            $tgl = [];
                            if ($b !== null) {
                                foreach ($b as $j) {
                                    if (count($j->monitoringPembelajarans) !== 0) {
                                        foreach ($j->monitoringPembelajarans as $m) {
                                            array_push($keterangan, $m->keterangan);
                                            array_push($tgl, $m->tanggal);
                                            if ($m->status_validasi === 'tidak valid') {
                                                $date1 = (float) substr($m->waktu_mulai, 0, -3);
                                                $date2 = (float) substr($m->waktu_berakhir, 0, -3);
                                                $jml = $jml + ($date2 - $date1);
                                            }
                                        }
                                    }
                                }
                            }
                        @endphp
                        <td align="center">
                            {{ $jml === 0 ? '0' : $jml }}</td>
                        <td align="center">
                            @if ($jml === 0)
                                {{ '100%' }}
                            @else
                                @php
                                    $data1 = $jml;
                                    $data2 = $diff;
                                    $total = round((($data2 - $data1) / $data2) * 100);
                                @endphp
                                {{ $total . '%' }}
                            @endif
                        </td>
                        <td>
                            {{-- @foreach ($keterangan as $k)
                                {{ $k }}
                            @endforeach --}}

                            @foreach ($keterangan as $key => $k)
                                @if ($k !== null)
                                    {{ $tgl[$key] . ' : ' . $k }}
                                    <br>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            @endforeach
        @endif

    </tbody>

</table>
