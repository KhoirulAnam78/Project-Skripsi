<div>
    <div class="row mx-2 mb-3">
        <div class="col-lg-4 col-md-4">
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
        </div>
    </div>
    <div class="row mx-3">
        @if ($kegiatan->narasumber == 1)
            <div class="table-responsive text-nowrap mb-3">
                <table class="table table-striped align-top" id="example">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Topik</th>
                            <th>Narasumber</th>
                            <th>Presensi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @if (count($monitoring) === 0)
                            <tr>
                                <td colspan='9' align="center"><span>Tidak ada data</span></td>
                            </tr>
                        @else
                            @foreach ($monitoring as $m)
                                <tr>
                                    <td>{{ ($monitoring->currentpage() - 1) * $monitoring->perpage() + $loop->index + 1 }}
                                    </td>
                                    <td>{{ $m->tanggal }}</td>
                                    <td>{{ substr($m->waktu_mulai, 0, -3) . '-' . substr($m->waktu_berakhir, 0, -3) }}
                                    </td>
                                    <td>{{ $m->topik }}</td>
                                    <td style="white-space: normal">{{ $m->narasumber->nama }}</td>
                                    <td>{{ ucwords($m->kehadiranKegnas->first()->status) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                </table>
                @if (count($monitoring) !== 0)
                    {{ $monitoring->links() }}
                @endif
            </div>
        @else
            <div class="table-responsive text-nowrap mb-3">
                <table class="table table-striped align-top" id="example">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Presensi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @if (count($monitoring) === 0)
                            <tr>
                                <td colspan='9' align="center"><span>Tidak ada data</span></td>
                            </tr>
                        @else
                            @foreach ($monitoring as $m)
                                <tr>
                                    <td>{{ ($monitoring->currentpage() - 1) * $monitoring->perpage() + $loop->index + 1 }}
                                    </td>
                                    <td>{{ $m->tanggal }}</td>
                                    <td>{{ substr($m->waktu_mulai, 0, -3) . '-' . substr($m->waktu_berakhir, 0, -3) }}
                                    </td>
                                    <td>{{ ucwords($m->kehadiranKegiatan->first()->status) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                </table>
                @if (count($monitoring) !== 0)
                    {{ $monitoring->links() }}
                @endif
            </div>
        @endif
    </div>
</div>
