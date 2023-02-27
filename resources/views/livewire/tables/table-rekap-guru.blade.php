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
    {{-- @php
          dd($guru);
      @endphp --}}
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
                        <td>{{ $b->first()->mataPelajaran->nama }}</td>
                        @php
                            $diff = new DateTime('00:00');
                            foreach ($b as $j) {
                                $date1 = new DateTime(substr($j->waktu_mulai, 0, -3));
                                $date2 = new DateTime(substr($j->waktu_berakhir, 0, -3));
                                $diff = $diff->sub($date2->diff($date1));
                            }
                        @endphp
                        <td align="center">{{ $diff->format('g.i') }}</td>

                        @php
                            $jml = new DateTime('00:00');
                            foreach ($b as $j) {
                                if (count($j->monitoringPembelajarans) !== 0) {
                                    foreach ($j->monitoringPembelajarans as $m) {
                                        if ($m->status_validasi === 'tidak valid') {
                                            $date1 = new DateTime(substr($m->waktu_mulai, 0, -3));
                                            $date2 = new DateTime(substr($m->waktu_berakhir, 0, -3));
                                            $jml = $jml->sub($date2->diff($date1));
                                        }
                                    }
                                }
                            }
                        @endphp
                        <td align="center">{{ $jml->format('g.i') === '12.00' ? '0' : $jml->format('g.i') }}
                        </td>
                        <td align="center">
                            @if ($jml->format('g.i') === '12.00')
                                {{ '100%' }}
                            @else
                                @php
                                    $data1 = $jml->format('g.i');
                                    $data2 = $diff->format('g.i');
                                    $data1int = (int) $data1;
                                    $data2int = (int) $data2;
                                    $total = round((($data2int - $data1int) / $data2int) * 100);
                                @endphp
                                {{ $total . '%' }}
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    @foreach ($g->jadwalPelajarans->groupBy('mata_pelajaran_id') as $key => $b)
                        @if ($loop->first)
                            @continue
                        @endif
                        <tr>
                            <td>{{ $b->first()->mataPelajaran->nama }}</td>
                            @php
                                $diff = new DateTime('00:00');
                                foreach ($b as $j) {
                                    $date1 = new DateTime(substr($j->waktu_mulai, 0, -3));
                                    $date2 = new DateTime(substr($j->waktu_berakhir, 0, -3));
                                    $diff = $diff->sub($date2->diff($date1));
                                }
                            @endphp
                            <td align="center">{{ $diff->format('g.i') }}</td>

                            @php
                                $jml = new DateTime('00:00');
                                foreach ($b as $j) {
                                    if (count($j->monitoringPembelajarans) !== 0) {
                                        foreach ($j->monitoringPembelajarans as $m) {
                                            if ($m->status_validasi === 'tidak valid') {
                                                $date1 = new DateTime(substr($m->waktu_mulai, 0, -3));
                                                $date2 = new DateTime(substr($m->waktu_berakhir, 0, -3));
                                                $jml = $jml->sub($date2->diff($date1));
                                            }
                                        }
                                    }
                                }
                            @endphp
                            <td align="center">
                                {{ $jml->format('g.i') === '12.00' ? '0' : $jml->format('g.i') }}</td>
                            <td align="center">
                                @if ($jml->format('g.i') === '12.00')
                                    {{ '100%' }}
                                @else
                                    @php
                                        $data1 = $jml->format('g.i');
                                        $data2 = $diff->format('g.i');
                                        $data1int = (int) $data1;
                                        $data2int = (int) $data2;
                                        $total = round((($data2int - $data1int) / $data2int) * 100);
                                    @endphp
                                    {{ $total . '%' }}
                                @endif
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        @endif

    </tbody>

</table>
