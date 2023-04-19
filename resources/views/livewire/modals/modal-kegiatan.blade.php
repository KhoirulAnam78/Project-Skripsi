<!-- Modal Input-->
<div class="modal fade" id="inputModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Tambah Data Kegiatan</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="nama" class="form-label">Nama Kegiatan</label>
                            <input type="text" id="nama" class="form-control" placeholder="Kepemimpinan"
                                wire:model="nama" />
                            @error('nama')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-3">
                            <label for="narasumber" class="form-label">Narasumber</label>
                            <select name="narasumber" class="form-control" id="narasumber" wire:model="narasumber">
                                <option value="">Pilih</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                            @error('narasumber')
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

{{-- MODAL EDIT --}}
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Edit Mata Pelajaran</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="update">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="nama" class="form-label">Nama Kegiatan</label>
                            <input type="text" id="nama" class="form-control" placeholder="Kepemimpinan"
                                wire:model="nama" />
                            @error('nama')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-3">
                            <label for="narasumber" class="form-label">Narasumber</label>
                            <select disabled name="narasumber" id="narasumber" class="form-control"
                                wire:model="narasumber">
                                <option value="{{ $narasumber }}">{{ $narasumber == true ? 'Ya' : 'Tidak' }}</option>
                            </select>
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
                <button type="submit" class="btn btn-danger" wire:click="deleteKegiatanData()">Delete</button>
            </div>
        </div>
    </div>
</div>
