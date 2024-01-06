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
    <div class="row mx-2 mb-3 justify-content-start">
        <div class="col-lg-2">
            <a class="btn btn-info mb-2 text-white" wire:click="export({{ $filterKelas }},{{ $filterMapel }})"
                style="background-color:#F0AD4E ;border-color: #F0AD4E"><i class='bx bxs-file-export'></i>
                Export</a>
        </div>
    </div>
    <div class="row mx-2 mb-3">
        @can('adpim')
            <div class="col-lg-3 col-md-3 mb-3 mx-2">
                <label for="tahun_akademik_id" class="form-label">Tahun Akademik</label>
                <select wire:model="filterTahunAkademik" id="filterTahunAkademik" class="form-select">
                    @foreach ($tahunAkademik as $ta)
                        <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                    @endforeach
                </select>
            </div>
        @endcan
        <div class="col-lg-4 mb-3 col-md-4">
            <label for="kelas_id" class="form-label">Kelas</label>
            <select wire:model="filterKelas" id="kelas_id" class="form-select">
                @if (count($kelas) !== 0)
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                @else
                    <option value="">Tidak ada kelas</option>
                @endif
            </select>
        </div>
        <div class="col-lg-3 col-md-3 mx-2">
            <label for="tanggalAwal" class="form-label">Tanggal Awal</label>
            <input type="date" wire:model="tanggalAwal" name="tanggalAwal" id="tanggalAwal" class="form-control" />
            @error('tanggalAwal')
                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                    {{ $message }}</span>
            @enderror
        </div>
        <div class="col-lg-3 col-md-3 mx-2">
            <label for="tanggalAkhir" class="form-label">Tanggal Akhir</label>
            <input type="date" wire:model="tanggalAkhir" name="tanggalAkhir" id="tanggalAkhir"
                class="form-control" />
            @error('tanggalAkhir')
                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                    {{ $message }}</span>
            @enderror
        </div>
        <div class="col-lg-4 col-md-4">
            <label for="japel_id" class="form-label">Mata Pelajaran</label>
            <select wire:model="filterMapel" id="japel_id" class="form-select">
                @if (count($mapel) !== 0)
                    @foreach ($mapel as $m)
                        <option value="{{ $m->id }}">{{ $m->nama }}</option>
                    @endforeach
                @else
                    <option selected value="">Tidak ada jadwal pelajaran</option>
                @endif
            </select>
        </div>
    </div>
    <div class="row mx-3">
        <div class="table-responsive text-nowrap mb-3">
            <table class="table table-striped align-top" id="examplei">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Topik Pembelajaran</th>
                        <th>Guru</th>
                        <th>Waktu</th>
                        <th>Jml Siswa</th>
                        <th>H</th>
                        <th>I</th>
                        <th>S</th>
                        <th>A</th>
                        <th>DD</th>
                        <th>DL</th>
                        <th>Status</th>
                        <th>Validator</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if (count($pertemuan) === 0)
                        <tr>
                            <td colspan='13' align="center"><span>Tidak ada data</span></td>
                        </tr>
                    @else
                        @foreach ($pertemuan as $s)
                            <tr>
                                <td>{{ ($pertemuan->currentpage() - 1) * $pertemuan->perpage() + $loop->index + 1 }}
                                </td>
                                <td>{{ $s->tanggal }}</td>
                                <td style="white-space: normal">{{ $s->topik }}</td>
                                <td>{{ $s->guru }}</td>
                                <td>{{ substr($s->waktu_mulai, 0, -3) . '-' . substr($s->waktu_berakhir, 0, -3) }}</td>
                                <td align="center">{{ $s->total }}</td>
                                <td align="center">{{ $s->hadir }}
                                </td>
                                <td align="center">{{ $s->izin }}
                                </td>
                                <td align="center">{{ $s->sakit }}
                                </td>
                                <td align="center">{{ $s->alfa }}
                                </td>
                                <td align="center">
                                    {{ $s->dd }}
                                </td>
                                <td align="center">
                                    {{ $s->dl }}
                                </td>
                                <td>
                                    @if ($s->status_validasi === 'terlaksana')
                                        <span class="badge bg-label-info me-1">Terlaksana</span>
                                    @else
                                        <span
                                            class="badge bg-label-danger me-1">{{ ucfirst($s->status_validasi) }}</span>
                                    @endif
                                </td>
                                <td>{{ $s->piket === null ? 'Admin' : $s->piket }}</td>
                                <td><button wire:click="detail({{ $s->monitoring_pembelajaran_id }})"
                                        class="btn btn-primary"><i class='bx bx-show'></i></button></td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>

            </table>
            @if (count($pertemuan) !== 0)
                {{ $pertemuan->links() }}
            @endif
        </div>
    </div>
    @include('livewire.modals.modal-detail')
    <script>
        window.addEventListener('show-detail-modal', event => {
            $('#showModal').modal('show');
        });
    </script>

</div>
