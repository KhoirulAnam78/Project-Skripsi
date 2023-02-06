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

    @if (session()->has('importError'))
        <div class="mb-2 mx-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach (session('importError') as $err)
                    Error pada baris ke {{ $err->row() }} : {{ ' ' . $err->errors()[0] }} <br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="mx-3 my-2">
        <p>Pilih tahun akademik dan kelas terlebih dahulu !</p>
    </div>
    <div class="row justify-content-start">
        <div class="col-lg-3 col-md-3 mb-3 mx-3">
            <label for="tahun_akademik_id" class="form-label">Tahun Akademik</label>
            <select wire:model="filterTahunAkademik" id="filterTahunAkademik" class="form-select">
                <option value=''>Pilih</option>
                @foreach ($tahun_akademik as $ta)
                    <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-3 mb-3 mx-3">
            <label for="kelas_id" class="form-label">Kelas</label>
            <select wire:model="filterKelas" id="kelas_id" class="form-select">
                @if ($kelas !== null)
                    <option value="">Pilih</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                @else
                    <option>Pilih tahun akademik</option>
                @endif
            </select>
        </div>
    </div>
    <div class="mx-3 my-2">
        <a href="" data-bs-toggle="modal" data-bs-target="#inputModal"
            class="btn btn-primary active mb-2 {{ ($filterTahunAkademik === '' or $filterKelas === '' or $allow === false) ? 'disabled' : '' }}"><i
                class='bx bx-add-to-queue'></i> Tambah</a>
        <a href=""
            class="btn btn-success active mb-2 {{ ($filterTahunAkademik === '' or $filterKelas === '' or $allow === false) ? 'disabled' : '' }}"
            data-bs-toggle="modal" data-bs-target="#importModal"
            style="background-color: rgb(0, 185, 0);border-color: rgb(0, 185, 0)"><i class='bx bxs-file-import'></i>
            Import</a>
        <a class="btn btn-info mb-2 text-white {{ ($filterTahunAkademik === '' or $filterKelas === '') ? 'disabled' : '' }}"
            wire:click="export()" style="background-color: rgb(0, 143, 0);border-color: rgb(0, 143, 0)"><i
                class='bx bxs-file-export'></i>
            Export</a>
    </div>
    <div class="col-lg-4 col-md-4 mb-2 mx-3">
        <label for="search" class="form-label">Pencarian</label>
        <input type="text" wire:model="search" id="search" class="form-control"
            placeholder="Cari berdasarkan nama siswa" />
    </div>
    <div class="table-responsive text-nowrap mx-3 mb-3">
        <table class="table table-striped" id="examplei">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Actions</th>
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
                                <button class="btn btn-danger {{ $allow === false ? 'disabled' : '' }}"
                                    wire:click="deleteConfirmation({{ $s->id }})"><i
                                        class="bx bx-trash me-1"></i>
                                    Delete</button>
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
    @include('livewire.modals.modal-rombel')
    <script>
        window.addEventListener('close-modal', event => {
            $('#inputModal').modal('hide');
        });
        window.addEventListener('close-modal-import', event => {
            $('#importModal').modal('hide')
        })
        window.addEventListener('close-modal-delete', event => {
            $('#deleteModal').modal('hide')
        })
        window.addEventListener('show-delete-confirmation-modal', event => {
            $('#deleteModal').modal('show')
        });
    </script>
</div>
