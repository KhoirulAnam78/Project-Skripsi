<!-- Modal Input-->
<div class="modal fade" id="inputModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Tambah Data Tahun Akademik</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-0">
                            <label for="nama" class="form-label">Nama Tahun Akademik</label>
                            <input type="text" wire:model="nama" id="nama" class="form-control"
                                placeholder="Semester Genap 2022/2023" />
                            @error('nama')
                                <span class="error" style="font-size:12px; font-style:italic">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-0">
                            <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" wire:model="tgl_mulai" id="tgl_mulai" class="form-control" />
                            @error('tgl_mulai')
                                <span class="error" style="font-size:12px; font-style:italic">* {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-3">
                            <label for="tgl_berakhir" class="form-label">Tanggal Berakhir</label>
                            <input type="date" wire:model="tgl_berakhir" id="tgl_berakhir" class="form-control" />
                            @error('tgl_berakhir')
                                <span class="error" style="font-size:12px; font-style:italic">* {{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-0">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" wire:model.defer="status" id="status" class="form-select">
                                <option value="">Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak aktif">Tidak Aktif</option>
                            </select>
                            @error('status')
                                <span class="error" style="font-size:12px; font-style:italic">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <span style="font-size:12px; font-style:italic">Nore : Apabila status diisi aktif maka tahun
                        akademik yang aktif saat ini akan dinonaktifkan !</span>
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
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Edit Data Tahun Akademik</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="update">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-0">
                            <label for="nama" class="form-label">Nama Tahun Akademik</label>
                            <input type="text" wire:model="nama" id="nama" class="form-control"
                                placeholder="Semester Genap 2022/2023" />
                            @error('nama')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-0">
                            <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" wire:model="tgl_mulai" id="tgl_mulai" class="form-control" />
                            @error('tgl_mulai')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-3">
                            <label for="tgl_berakhir" class="form-label">Tanggal Berakhir</label>
                            <input type="date" wire:model="tgl_berakhir" id="tgl_berakhir"
                                class="form-control" />
                            @error('tgl_berakhir')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-0">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" wire:model.defer="status" id="status" class="form-select">
                                <option value="">Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak aktif">Tidak Aktif</option>
                            </select>
                            @error('status')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <span style="font-size:12px; font-style:italic">Nore : Apabila status diubah dari tidak aktif
                        menjadi aktif maka tahun
                        akademik yang aktif saat ini akan dinonaktifkan !</span>
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
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
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
                <button type="submit" class="btn btn-danger" wire:click="deleteTahunAkademikData()">Delete</button>
            </div>
        </div>
    </div>
</div>
