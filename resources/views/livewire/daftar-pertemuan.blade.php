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
                        <option value="{{ $m->id }}">{{ $m->nama }}</option>
                    @endforeach
                @else
                    <option selected>Tidak ada jadwal pelajaran</option>
                @endif
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-2 mx-3">
            <div wire:loading.delay
                class="z-50 static flex fixed left-0 top-0 bottom-0 w-full bg-gray-400 bg-opacity-50">
                <img src="https://paladins-draft.com/img/circle_loading.gif" width="50" height="50"
                    class="m-auto mt-1/4"> <span>Loading ...</span>
            </div>
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
                                <td>
                                    @if ($s->status_validasi === 'valid')
                                        <span class="badge bg-label-info me-1">Valid</span>
                                    @else
                                        <span class="badge bg-label-danger me-1">Tidak Valid</span>
                                    @endif
                                </td>
                                <td>{{ $s->guru_piket_id === null ? 'Admin' : $s->guru->nama }}</td>
                                <td><button wire:click="detail({{ $s->id }})" class="btn btn-primary"><i
                                            class='bx bx-show'></i></button></td>
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
