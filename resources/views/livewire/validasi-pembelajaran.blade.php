<div>
    @if (session()->has('message'))
        <div class="mb-2 mx-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
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
    </div>
    <div class="row mx-2">
        <div class="table-responsive text-nowrap mb-3">
            <table class="table table-striped align-top" id="example">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Waktu</th>
                        <th>Topik Pembelajaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if (count($jadwal) === 0 and count($jadwalPengganti) === 0)
                        <tr>
                            <td colspan='9' align="center"><span>Tidak ada data</span></td>
                        </tr>
                    @endif
                    @php
                        $a = 0;
                    @endphp

                    @if (count($jadwal) !== 0)
                        @foreach ($jadwal as $j)
                            <tr>
                                <td>{{ ++$a }}
                                </td>
                                <td>{{ $j->kelas->nama }}</td>
                                <td>{{ $j->mataPelajaran->nama }}</td>
                                <td>{{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }}</td>
                                <td style="white-space: normal">
                                    {{ count($j->monitoringPembelajarans) === 0 ? '' : $j->monitoringPembelajarans->first()->topik }}
                                </td>
                                <td>{{ count($j->monitoringPembelajarans) === 0 ? '' : $j->monitoringPembelajarans->first()->status_validasi }}
                                </td>
                                <td><button {{ count($j->monitoringPembelajarans) === 0 ? 'disabled' : '' }}
                                        wire:click="showId({{ $j->id }})" class="btn btn-primary"><i
                                            class='bx bx-show'></i>
                                    </button>
                                    <button {{ count($j->monitoringPembelajarans) === 0 ? 'disabled' : '' }}
                                        @if (count($j->monitoringPembelajarans) !== 0) @if ($j->monitoringPembelajarans->first()->status_validasi === 'valid')
                                {{ 'disabled' }} @endif
                                        @endif wire:click="showValid({{ $j->id }})"
                                        class="btn btn-success"><i class='bx bx-check'></i>
                                    </button>
                                    <button wire:click="presensi({{ $j->id }})" class="btn btn-danger"><i
                                            class='bx bx-x'></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                    @if (count($jadwalPengganti) !== 0)

                        @foreach ($jadwalPengganti as $j)
                            <tr>
                                <td>{{ ++$a }}
                                </td>
                                <td>{{ $j->jadwalPelajaran->kelas->nama }}</td>
                                <td>{{ $j->jadwalPelajaran->mataPelajaran->nama }}</td>
                                <td>{{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }}</td>
                                <td>{{ count($j->jadwalPelajaran->monitoringPembelajarans) === 0 ? '' : $j->jadwalPelajaran->monitoringPembelajarans->first()->topik }}
                                </td>
                                <td>{{ count($j->jadwalPelajaran->monitoringPembelajarans) === 0 ? '' : $j->jadwalPelajaran->monitoringPembelajarans->first()->status_validasi }}
                                </td>
                                <td><button
                                        {{ count($j->jadwalPelajaran->monitoringPembelajarans) === 0 ? 'disabled' : '' }}
                                        class="btn btn-primary" wire:click="showId({{ $j->jadwal_pelajaran_id }})"><i
                                            class='bx bx-show'></i>
                                    </button>
                                    <button
                                        {{ count($j->jadwalPelajaran->monitoringPembelajarans) === 0 ? 'disabled' : '' }}
                                        @if (count($j->jadwalPelajaran->monitoringPembelajarans) !== 0) @if ($j->jadwalPelajaran->monitoringPembelajarans->first()->status_validasi === 'valid')
                                        {{ 'disabled' }} @endif
                                        @endif
                                        class="btn btn-success"
                                        wire:click="showValid({{ $j->jadwal_pelajaran_id }})"><i
                                            class='bx bx-check'></i>
                                    </button>
                                    <button wire:click="presensi({{ $j->jadwal_pelajaran_id }})"
                                        class="btn btn-danger"><i class='bx bx-x'></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @include('livewire.modals.modal-validasi')
    <script>
        window.addEventListener('show-modal', event => {
            $('#showModal').modal('show');
        });
        window.addEventListener('close-edit-modal', event => {
            $('#editModal').modal('hide');
        });
        window.addEventListener('show-edit-modal', event => {
            $('#editModal').modal('show');
        });
        window.addEventListener('close-valid-modal', event => {
            $('#validModal').modal('hide');
        });
        window.addEventListener('show-valid-modal', event => {
            $('#validModal').modal('show');
        });
    </script>
</div>
