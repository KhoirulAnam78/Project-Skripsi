<div>
    @if (session()->has('message'))
        <div class="mb-2 mx-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-2 mx-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="row mx-2 mb-3">
        <div class="col-lg-4 col-md-4">
            <label for="rekap" class="form-label">Bulan</label>
            <select wire:model="filterRekap" id="rekap" class="form-select">
                <option value="Januari">Januari</option>
                <option value="Februari">Februari</option>
                <option value="Maret">Maret</option>
                <option value="April">April</option>
                <option value="Mei">Mei</option>
                <option value="Juni">Juni</option>
                <option value="Juli">Juli</option>
                <option value="Agustus">Agustus</option>
                <option value="September">September</option>
                <option value="Oktober">Oktober</option>
                <option value="November">November</option>
                <option value="Desember">Desember</option>
            </select>
        </div>
        <div class="col-lg-4 col-md-4">
            <label for="rekap" class="form-label">Minggu Ke</label>
            <select wire:model="filterRekap" id="rekap" class="form-select">
                <option value="pertama">Pertama</option>
                <option value="kedua">Kedua</option>
                <option value="ketiga">Ketiga</option>
                <option value="keempat">Keempat</option>
            </select>
        </div>
        {{-- <div class="col-lg-4 col-md-4">
            <label for="tanggalAwal" class="form-label">Tanggal Awal</label>
            <input type="date" wire:model="tanggalAwal" name="tanggalAwal" id="tanggalAwal" class="form-control" />
            @error('tanggalAwal')
                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                    {{ $message }}</span>
            @enderror
        </div>
        <div class="col-lg-4 col-md-4">
            <label for="tanggalAkhir" class="form-label">Tanggal Akhir</label>
            <input type="date" wire:model="tanggalAkhir" name="tanggalAkhir" id="tanggalAkhir"
                class="form-control" />
            @error('tanggalAkhir')
                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                    {{ $message }}</span>
            @enderror
        </div> --}}
    </div>
    {{-- <div class="row mx-2 mb-3">
        <div class="col-lg-4 col-md-4 mb-0">
            <label for="search" class="form-label">Pencarian</label>
            <input type="text" wire:model="search" id="search" class="form-control"
                placeholder="Cari berdasarkan nama siswa" />
        </div>
    </div> --}}

    <div class="row mx-3">
        @if ($filterRekap === 'minggu')
            <div class="table-responsive text-nowrap mb-3">
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
                                <tr>
                                    <td>{{ ($guru->currentpage() - 1) * $guru->perpage() + $loop->index + 1 }}
                                    </td>
                                    <td>{{ $g->nama }}</td>
                                    <td>{{ $g->kode_guru }}</td>
                                    <td>{{ $g->jadwalPelajarans->first()->mataPelajaran->nama }}</td>
                                    @php
                                        $diff = new DateTime('00:00');
                                        foreach ($g->jadwalPelajarans as $j) {
                                            $date1 = new DateTime(substr($j->waktu_mulai, 0, -3));
                                            $date2 = new DateTime(substr($j->waktu_berakhir, 0, -3));
                                            $diff = $diff->sub($date2->diff($date1));
                                        }
                                    @endphp
                                    <td>{{ $diff->format('g.i') }}</td>

                                    @php
                                        foreach ($g->jadwalPelajarans as $j) {
                                            $jml = new DateTime('00:00');
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
                                    <td>{{ $jml->format('g.i') === '12.00' ? '0' : $jml->format('g.i') }}</td>
                                    <td>
                                        @if ($jml->format('g.i') === '12.00')
                                            {{ '100%' }}
                                        @else
                                            @php
                                                $data1 = $jml->format('g.i');
                                                $data2 = $diff->format('g.i');
                                                $data1int = (int) $data1;
                                                $data2int = (int) $data2;
                                                $total = (($data2int - $data1int) / $data2int) * 100;
                                            @endphp
                                            {{ $total . '%' }}
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>

                </table>
                @if (count($guru) !== 0)
                    {{ $guru->links() }}
                @endif
            </div>

            {{-- <div class="table-responsive text-nowrap mb-3">
                <table class="table table-striped align-top" id="example">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama</th>
                            <th rowspan="2">Kode</th>
                            <th rowspan="2">Mata Pelajaran</th>
                            <th rowspan="2">Jam Wajib</th>
                            <th colspan="5" style="text-align: center !important">Hari/Tanggal</th>
                        </tr>
                        <tr>
                            <th>Senin</th>
                            <th>Selasa</th>
                            <th>Rabu</th>
                            <th>Kamis</th>
                            <th>Jumat</th>

                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @if (count($guru) === 0)
                            <tr>
                                <td colspan='9' align="center"><span>Tidak ada data</span></td>
                            </tr>
                        @else
                            @foreach ($guru as $g)
                                <tr>
                                    <td>{{ ($guru->currentpage() - 1) * $guru->perpage() + $loop->index + 1 }}
                                    </td>
                                    <td>{{ $g->nama }}</td>
                                    <td>{{ $g->kode_guru }}</td>
                                    <td>{{ $g->jadwalPelajarans->first()->mataPelajaran->nama }}</td>
                                    <td>20</td>
                                    <td>Terlaksana</td>
                                    <td>Terlaksana</td>
                                    <td>Terlaksana</td>
                                    <td>Terlaksana</td>
                                    <td>Terlaksana</td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>

                </table>
                @if (count($guru) !== 0)
                {{ $guru->links() }}
            @endif
            </div> --}}
        @endif
    </div>
</div>
