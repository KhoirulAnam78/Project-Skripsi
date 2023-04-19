<!-- Modal Input-->
<div class="modal fade" id="inputModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Tambah Data Angkatan</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" id="nama" class="form-control" placeholder=""
                                wire:model="nama" />
                            @error('nama')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-0">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" wire:model.defer="status" id="status" class="form-select">
                                <option value="">Status</option>
                                <option value="belum lulus">Belum Lulus</option>
                                <option value="lulus">Lulus</option>
                            </select>
                            @error('status')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 mb-2">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" wire:model="search2" id="search" class="form-control"
                            placeholder="Cari berdasarkan nama wali asrama" />
                    </div>
                    @error('waliAsrama')
                        <span class="error" style="font-size:12px; font-style:italic">*
                            {{ $message }}</span>
                    @enderror
                    <div class="table-responsive text-nowrap mb-3">
                        <table class="table table-striped" id="examplei">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Tambah</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @php
                                    $i = 0;
                                @endphp
                                @if ($wali === null)
                                    <tr>
                                        <td colspan='7' align="center"><span>Tidak ada data</span></td>
                                    </tr>
                                @else
                                    @foreach ($wali as $g)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $g->nama }}</td>
                                            <td align="center"><input type="checkbox" wire:model="waliAsrama"
                                                    wire:key="myUniqueWireKey-{{ $g->id }}"
                                                    value="{{ $g->id }}"></td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>

                        </table>
                        @if ($wali !== null)
                            {{ $wali->links() }}
                        @endif
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
                <h5 class="modal-title text-white" id="exampleModalLabel1">Edit Data Angkatan</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="update">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" id="nama" class="form-control" placeholder=""
                                wire:model="nama" />
                            @error('nama')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-0">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" wire:model.defer="status" id="status" class="form-select">
                                <option value="">Status</option>
                                <option value="belum lulus">Belum Lulus</option>
                                <option value="lulus">Lulus</option>
                            </select>
                            @error('status')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    {{-- @dump($waliAsrama) --}}
                    <div class="col-lg-12 col-md-12 mb-2">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" wire:model="search2" id="search" class="form-control"
                            placeholder="Cari berdasarkan nama wali asrama" />
                    </div>
                    @error('waliAsrama')
                        <span class="error" style="font-size:12px; font-style:italic">*
                            {{ $message }}</span>
                    @enderror

                    <div class="table-responsive text-nowrap mb-3">
                        <table class="table table-striped" id="examplei">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Tambah</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @php
                                    $i = 0;
                                @endphp
                                @if ($wali === null)
                                    <tr>
                                        <td colspan='7' align="center"><span>Tidak ada data</span></td>
                                    </tr>
                                @else
                                    @foreach ($wali as $g)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $g->nama }}</td>
                                            <td align="center"><input type="checkbox" wire:model="waliAsrama"
                                                    wire:key="myUniqueWireKey-{{ $g->id }}"
                                                    value="{{ $g->id }}"></td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>

                        </table>
                        @if ($wali !== null)
                            {{ $wali->links() }}
                        @endif
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
