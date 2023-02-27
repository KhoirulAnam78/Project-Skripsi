<div>
    <div class="row mb-3">
        <div class="col-lg-4 col-md-4 mb-2 mx-3">
            <label for="search" class="form-label">Kegiatan</label>
            <select wire:model="filterKegiatan" id="filterKegiatan" class="form-select">
                <option value="Pembelajaran">Pembelajaran Dikelas</option>
            </select>
        </div>
        @if ($filterKegiatan === 'Pembelajaran')
            <div class="col-lg-2 col-md-3 mb-2 mx-3">
                <label for="search" class="form-label">Hari</label>
                <select wire:model="filterHari" id="filterHari" class="form-select">
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                </select>
            </div>
        @endif
    </div>
    @if ($filterKegiatan === 'Pembelajaran')
        <div class="table-responsive text-nowrap mx-3 mb-3">
            <table class="table table-striped" id="examplei">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Hari</th>
                        <th>Jam Pelajaran</th>
                        <th>Mata pelajaran</th>
                        <th>Guru</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if (count($jadwalPelajaran) === 0)
                        <tr>
                            <td colspan='7' align="center"><span>Tidak ada data</span></td>
                        </tr>
                    @else
                        @foreach ($jadwalPelajaran as $j)
                            <tr>
                                <td>{{ ($jadwalPelajaran->currentpage() - 1) * $jadwalPelajaran->perpage() + $loop->index + 1 }}
                                </td>
                                <td>{{ $j->hari }}</td>
                                <td>{{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }}</td>
                                <td>{{ $j->mataPelajaran->nama }}</td>
                                <td>{{ $j->guru->nama }}</td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>

            </table>
            @if (count($jadwalPelajaran) !== 0)
                {{ $jadwalPelajaran->links() }}
            @endif
        </div>
    @endif

</div>
