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
            <label for="kelas_id" class="form-label">Kelas</label>
            <select wire:model="filterKelas" id="kelas_id" class="form-select">
                @if ($kelas !== null)
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                @else
                    <option>Tidak ada kelas</option>
                @endif
            </select>
        </div>
        <div class="col-lg-4 col-md-4">
            <label for="japel_id" class="form-label">Mata Pelajaran</label>
            <select wire:model="filterMapel" id="japel_id" class="form-select">
                @if (count($mapel) !== 0)
                    @foreach ($mapel as $m)
                        <option value="{{ $m->id }}">{{ $m->mataPelajaran->nama }}</option>
                    @endforeach
                @else
                    <option selected>Tidak ada jadwal pelajaran</option>
                @endif
            </select>
        </div>
    </div>

    <div class="row mx-3">
        <div class="table-responsive text-nowrap mb-3">
            <table class="table table-striped" id="examplei">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Topik Pembelajaran</th>
                        <th>Waktu</th>
                        <th>Jml Siswa</th>
                        <th>H</th>
                        <th>I</th>
                        <th>S</th>
                        <th>A</th>
                        <th>DD</th>
                        <th>DL</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if (count($pertemuan) === 0)
                        <tr>
                            <td colspan='9' align="center"><span>Tidak ada data</span></td>
                        </tr>
                    @else
                        @foreach ($pertemuan as $s)
                            <tr>
                                <td>{{ ($pertemuan->currentpage() - 1) * $pertemuan->perpage() + $loop->index + 1 }}
                                </td>
                                <td>{{ $s->tanggal }}</td>
                                <td>{{ $s->topik }}</td>
                                <td>{{ substr($s->waktu_mulai, 0, -3) . '-' . substr($s->waktu_berakhir, 0, -3) }}</td>
                                <td align="center">{{ $jml_siswa }}</td>
                                <td align="center">{{ count($s->kehadiranPembelajarans->where('status', 'hadir')) }}
                                </td>
                                <td align="center">{{ count($s->kehadiranPembelajarans->where('status', 'izin')) }}
                                </td>
                                <td align="center">{{ count($s->kehadiranPembelajarans->where('status', 'sakit')) }}
                                </td>
                                <td align="center">{{ count($s->kehadiranPembelajarans->where('status', 'alfa')) }}
                                </td>
                                <td align="center">
                                    {{ count($s->kehadiranPembelajarans->where('status', 'dinas dalam')) }}
                                </td>
                                <td align="center">
                                    {{ count($s->kehadiranPembelajarans->where('status', 'dinas luar')) }}
                                </td>
                                <td><a class="btn btn-primary text-white"wire:click="edit({{ $k->id }})"><i
                                            class="bx bx-edit-alt"></i>
                                    </a></td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>

            </table>
            {{-- @if (count($pertemuan) !== 0)
                {{ $pertemuan->links() }} --}}
            {{-- @endif --}}
        </div>
    </div>
</div>
