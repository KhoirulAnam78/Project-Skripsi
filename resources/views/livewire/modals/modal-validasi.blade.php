<div class="modal fade" id="showModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Lihat Pembelajaran</h5>
                <button type="button" class="btn-close"data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="topik" class="form-label">Topik/Agenda Pembelajaran</label>
                            <textarea disabled name="topik" wire:model="topik" id="topik" class="form-control"></textarea>
                            @error('topik')
                                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea disabled name="keterangan" wire:model="keterangan" id="keterangan" class="form-control"></textarea>
                            @error('keterangan')
                                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="status" class="form-label">Status</label>
                            <input class="form-control" disabled type="text" value="{{ ucwords($status) }}"
                                id="status" name="status">
                        </div>
                    </div>
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
                                            <td>{{ ($siswa->currentpage() - 1) * $siswa->perpage() + $loop->index + 1 }}
                                            </td>
                                            <td>{{ $s->nisn }}</td>
                                            <td>{{ $s->nama }}</td>
                                            <td>
                                                <input disabled type="radio" id="presensihadir"
                                                    name="presensi.{{ $s->id }}" value='hadir'
                                                    wire:model="presensi.{{ $s->id }}">
                                                H <span class="mx-1"></span>
                                                <input disabled type="radio" id="presensiIzin"
                                                    name="presensi.{{ $s->id }}" value='izin'
                                                    wire:model="presensi.{{ $s->id }}">
                                                I <span class="mx-1"></span>
                                                <input disabled type="radio" id="presensiSakit"
                                                    name="presensi.{{ $s->id }}" value='sakit'
                                                    wire:model="presensi.{{ $s->id }}">
                                                S <span class="mx-1"></span>
                                                <input disabled type="radio" id="presensiAlfa"
                                                    name="presensi.{{ $s->id }}" value='alfa'
                                                    wire:model="presensi.{{ $s->id }}">
                                                A <span class="mx-1"></span>
                                                <input disabled type="radio" id="presensiDinasDalam"
                                                    name="presensi.{{ $s->id }}" value='dinas dalam'
                                                    wire:model="presensi.{{ $s->id }}">
                                                DD <span class="mx-1"></span>
                                                <input disabled type="radio" id="presensiDinasLuar"
                                                    name="presensi.{{ $s->id }}" value='dinas luar'
                                                    wire:model="presensi.{{ $s->id }}">
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
                <div class="modal-footer">
                    <button type="button" wire:click="empty()" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Pembelajaran tidak terlaksana / Guru tidak
                    hadir
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="tidakValid">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="topik" class="form-label">Topik/Agenda Pembelajaran</label>
                            <textarea name="topik" wire:model="topik" id="topik" class="form-control"></textarea>
                            @error('topik')
                                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" wire:model="keterangan" id="keterangan" class="form-control"></textarea>
                            @error('keterangan')
                                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="status" class="form-label">Status<span class="text-danger d-block"
                                    style="color:red; font-size:10px !important; font-style:italic">*
                                    Status akan menjadi tidak terlaksana</span></label>
                            <input class="form-control" disabled type="text" value="Tidak Terlaksana">
                        </div>
                    </div>
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
                                            <td>{{ ($siswa->currentpage() - 1) * $siswa->perpage() + $loop->index + 1 }}
                                            </td>
                                            <td>{{ $s->nisn }}</td>
                                            <td>{{ $s->nama }}</td>
                                            <td>
                                                <input type="radio" id="presensihadir"
                                                    name="presensi.{{ $s->id }}" value='hadir'
                                                    wire:model="presensi.{{ $s->id }}">
                                                H <span class="mx-1"></span>
                                                <input type="radio" id="presensiIzin"
                                                    name="presensi.{{ $s->id }}" value='izin'
                                                    wire:model="presensi.{{ $s->id }}">
                                                I <span class="mx-1"></span>
                                                <input type="radio" id="presensiSakit"
                                                    name="presensi.{{ $s->id }}" value='sakit'
                                                    wire:model="presensi.{{ $s->id }}">
                                                S <span class="mx-1"></span>
                                                <input type="radio" id="presensiAlfa"
                                                    name="presensi.{{ $s->id }}" value='alfa'
                                                    wire:model="presensi.{{ $s->id }}">
                                                A <span class="mx-1"></span>
                                                <input type="radio" id="presensiDinasDalam"
                                                    name="presensi.{{ $s->id }}" value='dinas dalam'
                                                    wire:model="presensi.{{ $s->id }}">
                                                DD <span class="mx-1"></span>
                                                <input type="radio" id="presensiDinasLuar"
                                                    name="presensi.{{ $s->id }}" value='dinas luar'
                                                    wire:model="presensi.{{ $s->id }}">
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
                <div class="modal-footer">
                    <button type="button" wire:click="empty()" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL VALID --}}
<div class="modal fade" id="validModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Validasi Pembelajaran</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Status pembelajaran akan menjadi terlaksana</h6>
                <p style="font-style: italic">* keterangan guru tidak hadir akan dikosongkan (jika ada)</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="empty()" class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-primary" wire:click="valid()">Terlaksana</button>
            </div>
        </div>
    </div>
</div>
