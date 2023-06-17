<!-- Modal Input-->
<div class="modal fade" id="inputModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Tambah Data Siswa</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="col-lg-12 col-md-12 mb-2 mx-3">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" wire:model="search2" id="search" class="form-control"
                            placeholder="Cari berdasarkan nama siswa" />
                    </div>
                    <div class="table-responsive text-nowrap mx-3 mb-3">
                        <table class="table table-striped" id="examplei">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Tambah</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">

                                @if ($dataSiswa === null)
                                    <tr>
                                        <td colspan='7' align="center"><span>Tidak ada data</span></td>
                                    </tr>
                                @else
                                    @foreach ($dataSiswa as $g)
                                        <tr>
                                            <td>{{ ($dataSiswa->currentpage() - 1) * $dataSiswa->perpage() + $loop->index + 1 }}
                                            </td>
                                            <td>{{ $g->nisn }}</td>
                                            <td>{{ $g->nama }}</td>
                                            <td align="center"><input type="checkbox" wire:model="selectedSiswa"
                                                    value="{{ $g->id }}"></td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>

                        </table>
                        @if ($dataSiswa !== null)
                            {{ $dataSiswa->links() }}
                        @endif
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" wire:click="empty()"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit"
                        class="btn btn-primary {{ count($selectedSiswa) === 0 ? 'disabled' : '' }}">Simpan</button>
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
                <p style="font-style: italic">* Siswa akan dihapus dari rombongan belajar ini !</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="empty()" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-danger" wire:click="deleteRombelData()">Delete</button>
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
                <h5 class="modal-title text-white" id="exampleModalLabel1">Import Data Rombel</h5>
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
                            <a href="/download-template-rombel"class="btn btn-primary active"><i
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

{{-- Modal LULUS --}}
{{-- IMPORT DATA --}}
<div class="modal fade" id="modalLulus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Ubah Status Siswa Menjadi Lulus</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="import">
                <div class="modal-body">
                    <div class="table-responsive text-nowrap mx-3 mb-3">
                        <table class="table table-striped" id="examplei">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
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
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                    @if (count($siswa) !== 0)
                        {{ $siswa->links() }}
                    @endif
                    <span style="font-size:12px; font-style:italic">Note : Siswa pada daftar diatas akan diubah
                        statusnya menjadi lulus !</span>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="empty()" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary" wire:click="setLulus()">Set Lulus</button>
                </div>
            </form>
        </div>
    </div>
</div>
