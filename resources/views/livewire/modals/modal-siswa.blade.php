<!-- Modal Input-->
<div class="modal fade" id="inputModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Tambah Data Siswa</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col mb-0">
                            <label for="nisn" class="form-label">NISN</label>
                            <input type="text" wire:model="nisn" id="nisn" class="form-control"
                                placeholder="1234567890" />
                            @error('nisn')
                                <span class="error" style="font-size:12px; font-style:italic">* {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" id="nama" class="form-control" placeholder="Khoirul Anam"
                                wire:model="nama" />
                            @error('nama')
                                <span class="error" style="font-size:12px; font-style:italic">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-0">
                            <label for="no_telp" class="form-label">No Telp</label>
                            <input type="text" wire:model="no_telp" id="no_telp" class="form-control"
                                placeholder="08 atau +628" />
                            @error('no_telp')
                                <span class="error" style="font-size:12px; font-style:italic">* {{ $message }}</span>
                            @enderror
                        </div>
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
                    <div class="row g-2">
                        <div class="col mb-0">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" wire:model="username" id="username" class="form-control"
                                placeholder="khoirul1234" value="{{ $nisn }}" />
                            <input class="form-check-input" type="checkbox" id='checkbox'
                                {{ $checkboxUname === true ? 'checked' : '' }} wire:click="defaultUname()">
                            <span style="font-size: 12px">Username Default</span>
                            @error('username')
                                <span class="error" style="font-size:12px; font-style:italic">* {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-0 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" wire:model="password" class="form-control"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            <input class="form-check-input" type="checkbox" id='checkbox'
                                {{ $checkbox === true ? 'checked' : '' }} wire:click="defaultPw()">
                            <span style="font-size: 12px">Password Default</span>
                            @error('password')
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
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Edit Data Guru</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="update">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col mb-0">
                            <label for="nisn" class="form-label">NISN</label>
                            <input type="text" wire:model="nisn" id="nisn" class="form-control"
                                placeholder="1234567890" />
                            @error('nisn')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" id="nama" class="form-control" placeholder="Khoirul Anam"
                                wire:model="nama" />
                            @error('nama')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-0">
                            <label for="no_telp" class="form-label">No Telp</label>
                            <input type="text" wire:model="no_telp" id="no_telp" class="form-control"
                                placeholder="08 atau +628" />
                            @error('no_telp')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
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
                <button type="submit" class="btn btn-danger" wire:click="deleteSiswaData()">Delete</button>
            </div>
        </div>
    </div>
</div>

{{-- IMPORT DATA --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Import Data</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="import">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="file" class="form-label">file</label>
                            <input type="file" accept="xlsx,xls" id="file" class="form-control"
                                wire:model="file" />
                            @error('file')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <a href="/download-template-siswa"class="btn btn-success active"><i
                                    class='bx bxs-download'></i>Download Template</a>
                        </div>
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
