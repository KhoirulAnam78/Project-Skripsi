<!-- Modal Input-->
<div class="modal fade" id="inputModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Tambah Jadwal Pengganti</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row mb-3 g-2">
                        <div class="col mb-0">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" wire:model="tanggal" id="tanggal" class="form-control" />
                            @error('tanggal')
                                <span class="error" style="font-size:12px; font-style:italic">* {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-3">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <select wire:model="filterKelas" id="kelas_id" class="form-select">
                                @if (count($kelas) !== 0)
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                @else
                                    <option value=''>Tidak ada kelas</option>
                                @endif
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="japel_id" class="form-label">Jadwal Mata Pelajaran</label>
                            <select wire:model="japel_id" id="japel_id" class="form-select">
                                @if (count($mapel) !== 0)
                                    @foreach ($mapel as $m)
                                        <option value="{{ $m->id }}">
                                            {{ $m->hari . ' : ' . '(' . $m->guru->nama . ') ' . $m->mataPelajaran->nama }}
                                        </option>
                                    @endforeach
                                @else
                                    <option selected>Tidak ada jadwal pelajaran</option>
                                @endif
                            </select>
                            @error('japel_id')
                                <span class="error" style="font-size:12px; font-style:italic">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                            <input type="time" id="waktu_mulai" class="form-control" wire:model="waktu_mulai" />
                            @error('waktu_mulai')
                                <span class="error" style="font-size:12px; font-style:italic">* {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-0">
                            <label for="waktu_berakhir" class="form-label">Waktu Berakhir </label>
                            <input type="time" wire:model="waktu_berakhir" id="waktu_berakhir"
                                class="form-control" />
                            @error('waktu_berakhir')
                                <span class="error" style="font-size:12px; font-style:italic">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" wire:click="empty()"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit-->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Edit Jadwal Pengganti</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="update">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col mb-0">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" wire:model="tanggal" id="tanggal" class="form-control" />
                            @error('tanggal')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-3">
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
                        <div class="col mb-3">
                            <label for="japel_id" class="form-label">Mata Pelajaran</label>
                            <select wire:model="japel_id" id="japel_id" class="form-select">
                                @if (count($mapel) !== 0)
                                    @foreach ($mapel as $m)
                                        <option value="{{ $m->id }}">
                                            {{ $m->hari . ' => ' . $m->mataPelajaran->nama }}
                                        </option>
                                    @endforeach
                                @else
                                    <option selected>Tidak ada jadwal pelajaran</option>
                                @endif
                            </select>
                            @error('japel_id')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                            <input type="time" id="waktu_mulai" class="form-control" wire:model="waktu_mulai" />
                            @error('waktu_mulai')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-0">
                            <label for="waktu_berakhir" class="form-label">Waktu Berakhir </label>
                            <input type="time" wire:model="waktu_berakhir" id="waktu_berakhir"
                                class="form-control" />
                            @error('waktu_berakhir')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" wire:click="empty()"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DELETE --}}
<div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Benar ingin menghapus data?</h6>
                <p style="font-style: italic">* Jika data digunakan didalam sistem maka tidak
                    akan bisa dihapus, Hal ini
                    untuk mempertahankan history data!</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="empty()" class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-danger" wire:click="deleteJadwalData()">Delete</button>
            </div>
        </div>
    </div>
</div>
