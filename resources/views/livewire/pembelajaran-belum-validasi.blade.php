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
                @if (count($kelas) !== 0)
                    <option value="">Semua</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                @else
                    <option value="">Tidak ada kelas</option>
                @endif
            </select>
        </div>
        {{-- @can('admin')
            <div class="col-lg-4 col-md-4">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" value="{{ $tanggal }}" wire:model="tanggal" name="tanggal" id="tanggal"
                    min="{{ $minDate }}" max="{{ \Carbon\Carbon::now()->translatedFormat('Y-m-d') }}"
                    class="form-control" />
                @error('tanggal')
                    <span class="error" style="color:red; font-size:12px; font-style:italic">*
                        {{ $message }}</span>
                @enderror
            </div>
        @endcan --}}
    </div>
    <div class="row mx-2">
        <div class="table-responsive text-nowrap mb-3">
            <table class="table table-striped table-bordered align-top" id="example">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Ringkasan</th>
                        {{-- <th>Mapel</th>
                        <th>Guru</th>
                        <th>Waktu</th> --}}
                        <th>Topik Pembelajaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    {{-- @if (count($jadwal) === 0 and count($jadwalPengganti) === 0)
                        <tr>
                            <td colspan='9' align="center"><span>Tidak ada data</span></td>
                        </tr>
                    @endif --}}
                    @php
                        $a = 0;
                    @endphp

                    @if (count($jadwal) !== 0)
                        @foreach ($jadwal as $j)
                            <tr>
                                <td>{{ ++$a }}
                                </td>
                                <td>{{ $j->tanggal }}</td>
                                <td>{{ $j->kelas }} <br>
                                    {{ $j->mata_pelajaran }} <br>
                                    {{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }} <br>
                                    {{ $j->guru }}
                                </td>
                                <td style="white-space: normal">
                                    {{ $j->topik }}
                                </td>
                                <td>
                                    {{ ucfirst($j->status_validasi) }} <br>
                                    @if ($j->status_validasi != 'belum tervalidasi')
                                        @if ($j->guru_piket && $j->topik)
                                            <span style='font-size:12px'>Divalidasi oleh : {{ $j->guru_piket }} </span>
                                        @elseif($j->topik)
                                            <span style='font-size:12px'>Divalidasi oleh : Administrator</span>
                                        @else
                                            -
                                        @endif

                                    @endif
                                </td>
                                <td><button {{ $j->topik === null ? 'disabled' : '' }}
                                        wire:click="showId({{ $j->monitoring_pembelajaran_id }})"
                                        class="btn btn-primary"><i class='bx bx-show'></i>
                                    </button>
                                    <button {{ $j->topik === null ? 'disabled' : '' }}
                                        @if ($j->topik === null) @if ($j->status_validasi === 'terlaksana')
                                {{ 'disabled' }} @endif
                                        @endif
                                        wire:click="showValid({{ $j->monitoring_pembelajaran_id }})"
                                        class="btn btn-success"><i class='bx bx-check'></i>
                                    </button>
                                    <button wire:click="presensi({{ $j->monitoring_pembelajaran_id }})"
                                        class="btn btn-danger"><i class='bx bx-x'></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                    {{-- @if (count($jadwalPengganti) !== 0)

                        @foreach ($jadwalPengganti as $j)
                            <tr>
                                <td>{{ ++$a }}
                                </td>
                                <td>{{ $j->jadwalPelajaran->kelas->nama }}</td>
                                <td>{{ $j->jadwalPelajaran->mataPelajaran->nama }}</td>
                                <td>{{ $j->jadwalPelajaran->guru->nama }}</td>
                                <td>{{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }}</td>
                                @php
                                    $data = $j->jadwalPelajaran->monitoringPembelajarans->where('tanggal', $tanggal);
                                @endphp
                                <td>{{ count($data) === 0 ? '' : $j->topik }}
                                </td>
                                <td>{{ count($data) === 0 ? '' : ucwords($j->status_validasi) }}
                                </td>
                                <td><button {{ count($data) === 0 ? 'disabled' : '' }} class="btn btn-primary"
                                        wire:click="showId({{ $j->jadwal_pelajaran_id }})"><i class='bx bx-show'></i>
                                    </button>
                                    <button {{ count($data) === 0 ? 'disabled' : '' }}
                                        @if (count($data) !== 0) @if ($j->status_validasi === 'terlaksana')
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
                    @endif --}}
                </tbody>
            </table>
        </div>
    </div>
    @include('livewire.modals.modal-validasi')

</div>

@push('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        document.addEventListener('alert-tidak-valid', function(e) {
            $('#editModal').modal('hide');
            swal(e.detail.info, e.detail.message, "success");
        });
        document.addEventListener('alert-valid', function(e) {
            $('#validModal').modal('hide');
            swal(e.detail.info, e.detail.message, "success");
        });
    </script>
    <script>
        window.addEventListener('show-modal', event => {
            $('#showModal').modal('show');
        });

        window.addEventListener('show-edit-modal', event => {
            $('#editModal').modal('show');
        });

        window.addEventListener('show-valid-modal', event => {
            $('#validModal').modal('show');
        });
    </script>
@endpush
