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
            <label for="tanggal" class="form-label">Jadwal Kegiatan</label>
            <input disabled type="text" value="{{ $hari }}" name="hari" id="hari"
                class="form-control" />
            @error('hari')
                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                    {{ $message }}</span>
            @enderror
        </div>
        @can('admin')
            <div class="col-lg-4 col-md-4">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" value="{{ $tanggal }}" wire:model="tanggal" name="tanggal" id="tanggal"
                    class="form-control" />
                @error('tanggal')
                    <span class="error" style="color:red; font-size:12px; font-style:italic">*
                        {{ $message }}</span>
                @enderror
            </div>
        @endcan
        @can('waliAsrama')
            <div class="col-lg-4 col-md-4">
                <label for="tanggal" class="form-label">Tanggal Hari Ini</label>
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
                                    <input {{ $allow === false ? 'disabled' : '' }} type="radio" id="presensihadir"
                                        name="presensi.{{ $s->id }}" value='hadir'
                                        wire:model="presensi.{{ $s->id }}">
                                    H <span class="mx-1"></span>
                                    <input {{ $allow === false ? 'disabled' : '' }} type="radio" id="presensiIzin"
                                        name="presensi.{{ $s->id }}" value='izin'
                                        wire:model="presensi.{{ $s->id }}">
                                    I <span class="mx-1"></span>
                                    <input {{ $allow === false ? 'disabled' : '' }} type="radio" id="presensiSakit"
                                        name="presensi.{{ $s->id }}" value='sakit'
                                        wire:model="presensi.{{ $s->id }}">
                                    S <span class="mx-1"></span>
                                    <input {{ $allow === false ? 'disabled' : '' }} type="radio" id="presensiAlfa"
                                        name="presensi.{{ $s->id }}" value='alfa'
                                        wire:model="presensi.{{ $s->id }}">
                                    A <span class="mx-1"></span>
                                    <input {{ $allow === false ? 'disabled' : '' }} type="radio"
                                        id="presensiDinasDalam" name="presensi.{{ $s->id }}"
                                        value='dinas dalam' wire:model="presensi.{{ $s->id }}">
                                    DD <span class="mx-1"></span>
                                    <input {{ $allow === false ? 'disabled' : '' }} type="radio"
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
                    <button class="btn btn-primary" {{ ($allow === false or $siswa->first() === null) ? 'disabled' : '' }}
                        wire:click="save()">Simpan</button>
                @else
                    <button style="background-color: rgb(0, 185, 0);border-color: rgb(0, 185, 0)" class="btn btn-success"
                        {{ $allow === false ? 'disabled' : '' }} wire:click="update()">Update</button>
                @endif
            @endcan
            @can('waliAsrama')
                @if ($update === false)
                    <button class="btn btn-primary"
                        @php
if(count($siswa) === 0){
                            echo 'disabled';
                            // dd('Filter Mapel');
                        } else if(\Carbon\Carbon::now()->translatedFormat('H:i') < $waktu_mulai){
                            echo 'disabled';
                        } else if(\Carbon\Carbon::now()->translatedFormat('H:i') > $waktu_berakhir){
                            echo 'disabled';
                        } else{
                            echo 'wire:click="save()"';
                        } @endphp>Simpan</button>
                @else
                    <button style="background-color: rgb(0, 185, 0);border-color: rgb(0, 185, 0)" class="btn btn-success"
                        @php
if(count($siswa) === 0){
                                                echo 'disabled';
                                            } else if(\Carbon\Carbon::now()->translatedFormat('H:i') < $waktu_mulai){
                                                echo 'disabled';
                                            } else if(\Carbon\Carbon::now()->translatedFormat('H:i') > $waktu_berakhir){
                                                echo 'disabled';
                                            } else {
                                                echo 'wire:click="update()"';
                                            } @endphp>Update</button>
                @endif
            @endcan
        </div>
    </div>
</div>
