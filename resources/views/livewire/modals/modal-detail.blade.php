<div class="modal fade" id="showModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Lihat Presensi Pertemuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea disabled name="keterangan" wire:model="keterangan" id="keterangan" class="form-control"></textarea>
                            @error('keterangan')
                                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
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
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
