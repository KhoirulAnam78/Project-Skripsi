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
                @if (count($kelas) !== 0)
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                @else
                    <option value="">Tidak ada kelas</option>
                @endif
            </select>
        </div>
        <div class="col-lg-4 col-md-4">
            <label for="filterMapel" class="form-label">Mata Pelajaran</label>
            <select wire:model="filterMapel" id="filterMapel" class="form-select">
                @if (count($mapel) !== 0)
                    @foreach ($mapel as $m)
                        <option value="{{ $m->id }}">{{ $m->mataPelajaran->nama }}</option>
                    @endforeach
                @endif
                @if (count($jadwal_pengganti) !== 0)
                    @foreach ($jadwal_pengganti as $j)
                        <option value="{{ $j->jadwal_pelajaran_id }}">{{ $j->jadwalPelajaran->mataPelajaran->nama }}
                            (pengganti)
                        </option>
                    @endforeach
                @endif
                @if (count($mapel) === 0 and count($jadwal_pengganti) === 0)
                    <option value="" selected>Tidak ada jadwal pelajaran</option>
                @endif

            </select>
        </div>
        @can('admin')
            <div class="col-lg-4 col-md-4">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" value="{{ $tanggal }}" wire:model="tanggal" name="tanggal" id="tanggal"
                    class="form-control" min="{{ $minDate }}"
                    max="{{ \Carbon\Carbon::now()->translatedFormat('Y-m-d') }}" />
                @error('tanggal')
                    <span class="error" style="color:red; font-size:12px; font-style:italic">*
                        {{ $message }}</span>
                @enderror
            </div>
        @endcan
        @can('guru')
            <div class="col-lg-4 col-md-4">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input disabled type="date" value="{{ $tanggal }}" name="tanggal" id="tanggal"
                    class="form-control" />
                @error('tanggal')
                    <span class="error" style="color:red; font-size:12px; font-style:italic">*
                        {{ $message }}</span>
                @enderror
            </div>
        @endcan
    </div>

    <div class="row mx-2 mb-3">
        <div class="col-lg-4 col-md-4">
            <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
            <input disabled type="time" id="waktu_mulai" class="form-control" value="{{ $waktu_mulai }}" />
            @error('waktu_mulai')
                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                    {{ $message }}</span>
            @enderror
        </div>
        <div class="col-lg-4 col-md-4">
            <label for="waktu_berakhir" class="form-label">Waktu Berakhir</label>
            <input disabled type="time" value="{{ $waktu_berakhir }}" id="waktu_berakhir" class="form-control" />
            @error('waktu_berakhir')
                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                    {{ $message }}</span>
            @enderror
        </div>
        <div class="col-lg-4 col-md-4">
            <label for="topik" class="form-label">Topik/Agenda Pembelajaran</label>
            <textarea required name="topik" wire:model="topik" id="topik" class="form-control"></textarea>
            @error('topik')
                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                    {{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="row mx-3">
        <div class="table-responsive text-nowrap mb-3">
            <table class="table table-striped" id="examplei">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Presensi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if (count($siswa) === 0)
                        <tr>
                            <td colspan='7' align="center"><span>Tidak ada data</span></td>
                        </tr>
                    @else
                        @foreach ($siswa as $s)
                            <tr>
                                <td>{{ ($siswa->currentpage() - 1) * $siswa->perpage() + $loop->index + 1 }}</td>
                                <td>{{ $s->nisn }}</td>
                                <td>{{ $s->nama }}</td>
                                <td>
                                    <input {{ $filterMapel === '' ? 'disabled' : '' }} type="radio"
                                        id="presensihadir" name="presensi.{{ $s->id }}" value='hadir'
                                        wire:model="presensi.{{ $s->id }}">
                                    H <span class="mx-1"></span>
                                    <input {{ $filterMapel === '' ? 'disabled' : '' }} type="radio" id="presensiIzin"
                                        name="presensi.{{ $s->id }}" value='izin'
                                        wire:model="presensi.{{ $s->id }}">
                                    I <span class="mx-1"></span>
                                    <input {{ $filterMapel === '' ? 'disabled' : '' }} type="radio"
                                        id="presensiSakit" name="presensi.{{ $s->id }}" value='sakit'
                                        wire:model="presensi.{{ $s->id }}">
                                    S <span class="mx-1"></span>
                                    <input {{ $filterMapel === '' ? 'disabled' : '' }} type="radio"
                                        id="presensiAlfa" name="presensi.{{ $s->id }}" value='alfa'
                                        wire:model="presensi.{{ $s->id }}">
                                    A <span class="mx-1"></span>
                                    <input {{ $filterMapel === '' ? 'disabled' : '' }} type="radio"
                                        id="presensiDinasDalam" name="presensi.{{ $s->id }}"
                                        value='dinas dalam' wire:model="presensi.{{ $s->id }}">
                                    DD <span class="mx-1"></span>
                                    <input {{ $filterMapel === '' ? 'disabled' : '' }} type="radio"
                                        id="presensiDinasLuar" name="presensi.{{ $s->id }}"
                                        value='dinas luar' wire:model="presensi.{{ $s->id }}">
                                    DL <span class="mx-1"></span>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>

            </table>
            @if (count($siswa) !== 0)
                {{ $siswa->links() }}
            @endif
        </div>
    </div>
    <div class="row mx-3 justify-content-end my-3">
        <div class="col-2">
            @can('admin')
                @if ($update === false)
                    <button class="btn btn-primary" {{ $filterMapel === '' ? 'disabled' : '' }}
                        wire:click="save()">Simpan</button>
                @else
                    <button style="background-color: rgb(0, 185, 0);border-color: rgb(0, 185, 0)" class="btn btn-success"
                        {{ $filterMapel === '' ? 'disabled' : '' }} wire:click="update()">Update</button>
                @endif

            @endcan
            @can('guru')
                @if ($update === false)
                    @if ($filterMapel === '')
                        <button class="btn btn-primary" disabled>Simpan</button>
                    @elseif (\Carbon\Carbon::now()->translatedFormat('H:i') < $waktu_mulai)
                        <button class="btn btn-primary" disabled>Simpan</button>
                    @elseif (\Carbon\Carbon::now()->translatedFormat('H:i') > $waktu_berakhir)
                        <button class="btn btn-primary" disabled>Simpan</button>
                    @else
                        <button class="btn btn-primary" wire:key="simpan" wire:click="save()">Simpan</button>
                    @endif
                @else
                    @if ($filterMapel === '')
                        <button style="background-color: rgb(0, 185, 0);border-color: rgb(0, 185, 0)"
                            class="btn btn-success" disabled>Update</button>
                    @elseif (\Carbon\Carbon::now()->translatedFormat('H:i') < $waktu_mulai)
                        <button style="background-color: rgb(0, 185, 0);border-color: rgb(0, 185, 0)"
                            class="btn btn-success" disabled>Update</button>
                    @elseif (\Carbon\Carbon::now()->translatedFormat('H:i') > $waktu_berakhir)
                        <button style="background-color: rgb(0, 185, 0);border-color: rgb(0, 185, 0)"
                            class="btn btn-success" disabled>Update</button>
                    @else
                        <button wire:key="update" style="background-color: rgb(0, 185, 0);border-color: rgb(0, 185, 0)"
                            class="btn btn-success" wire:click="update()">Update</button>
                    @endif
                @endif
            @endcan
        </div>
    </div>
</div>
