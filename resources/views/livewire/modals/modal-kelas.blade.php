<!-- Modal Input-->
<div class="modal fade" id="inputModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Tambah Data Kelas</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" id="nama" class="form-control" placeholder="Kelas X IPA 1"
                                wire:model="nama" />
                            @error('nama')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-0">
                            <label for="angkatan_id" class="form-label">Angkatan</label>
                            <select name="angkatan_id" wire:model.defer="angkatan_id" id="angkatan_id"
                                class="form-select">
                                <option value="">Pilih</option>
                                @foreach ($angkatan as $a)
                                    <option value="{{ $a->id }}">{{ $a->nama }}</option>
                                @endforeach
                            </select>
                            @error('angkatan_id')
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
                <h5 class="modal-title text-white" id="exampleModalLabel1">Edit Data Kelas</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="update">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" id="nama" class="form-control" placeholder="Kelas X IPA 1"
                                wire:model="nama" />
                            @error('nama')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-0">
                            <label for="angkatan_id" class="form-label">Angkatan</label>
                            <select name="angkatan_id" wire:model.defer="angkatan_id" id="angkatan_id"
                                class="form-select">
                                <option value="">Pilih</option>
                                @foreach ($angkatan as $a)
                                    <option value="{{ $a->id }}">{{ $a->nama }}</option>
                                @endforeach
                            </select>
                            @error('angkatan_id')
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
                <button type="submit" class="btn btn-danger" wire:click="deleteKelasData()">Delete</button>
            </div>
        </div>
    </div>
</div>

{{-- IMPORT DATA --}}
<div class="modal fade" id="importModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true" wire:ignore.self>
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
                            <div class="col-lg-2 col-md-2 mx-3">
                                <div wire:loading.delay
                                    class="z-50 static flex fixed left-0 top-0 bottom-0 w-full bg-gray-400 bg-opacity-50">
                                    <img src="https://paladins-draft.com/img/circle_loading.gif" width="50"
                                        height="50" class="m-auto mt-1/4"> <span>Loading ...</span>
                                </div>
                            </div>
                            @error('file')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <a href="/download-template-kelas"class="btn btn-primary"><i
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
