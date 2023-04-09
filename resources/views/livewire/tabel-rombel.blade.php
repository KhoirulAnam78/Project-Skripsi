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
        <div class="col-lg-4 col-md-4 mb-3 mx-3">
            <label for="tahun_akademik_id" class="form-label">Tahun Akademik</label>
            <select wire:model="filterTahunAkademik" id="filterTahunAkademik" class="form-select">
                @foreach ($tahun_akademik as $ta)
                    <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-3 mb-3 mx-3">
            <label for="kelas_id" class="form-label">Kelas</label>
            <select wire:model="filterKelas" id="kelas_id" class="form-select">
                @if ($kelas !== null)
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                @else
                    <option>Pilih tahun akademik</option>
                @endif
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-2 mx-3">
        <div wire:loading.delay class="z-50 static flex fixed left-0 top-0 bottom-0 w-full bg-gray-400 bg-opacity-50">
            <img src="https://paladins-draft.com/img/circle_loading.gif" width="50" height="50"
                class="m-auto mt-1/4"> <span>Loading ...</span>
        </div>
    </div>
    <div class="mx-3 my-2">
        @can('admin')

            @if ($allow !== false)
                <a href="" data-bs-toggle="modal" data-bs-target="#inputModal" wire:click="pageReset()"
                    class="btn btn-primary active mb-2 {{ $allow === false ? 'disabled' : '' }}"
                    style="background-color : #1052BA;border-color: #1052BA"><i class='bx bx-add-to-queue'></i> Tambah</a>
                <a href="" class="btn btn-success active mb-2 {{ $allow === false ? 'disabled' : '' }}"
                    data-bs-toggle="modal" data-bs-target="#importModal"
                    style="background-color: #5CB85C;border-color: #5CB85C"><i class='bx bxs-file-import'></i>
                    Import</a>
            @else
                <a href="" class="btn btn-primary active mb-2 disabled"
                    style="background-color : #1052BA;border-color: #1052BA"><i class='bx bx-add-to-queue'></i> Tambah</a>
                <a href="" class="btn btn-success active mb-2 disabled"
                    style="background-color: #5CB85C;border-color: #5CB85C"><i class='bx bxs-file-import'></i>
                    Import</a>
            @endif
        @endcan
        <a class="btn btn-info mb-2 text-white" wire:click="export()"
            style="background-color: #F0AD4E;border-color: #F0AD4E"><i class='bx bxs-file-export'></i>
            Export</a>
    </div>
    <div class="col-lg-4 col-md-4 mb-2 mx-3">
        <label for="search" class="form-label">Pencarian</label>
        <input type="text" wire:model="search" id="search" class="form-control"
            placeholder="Cari berdasarkan nama siswa" />
    </div>
    <div class="mx-3 my-3">

        @can('admin')
            @if ($allow !== false)
                <a href="" class="btn btn-info active mb-2 {{ $allow === false ? 'disabled' : '' }}"
                    wire:click="pageReset()" data-bs-toggle="modal" data-bs-target="#modalLulus"><i
                        class='bx bxs-graduation'></i>
                    Set Lulus</a>
            @else
                <a href="" class="btn btn-info active mb-2 disabled" data-bs-toggle="modal"
                    data-bs-target="#modalLulus"><i class='bx bxs-graduation'></i>
                    Set Lulus</a>
            @endif
        @endcan
    </div>
    <div class="table-responsive text-nowrap mx-3 mb-3">
        <table class="table table-striped" id="examplei">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    @can('admin')
                        <th>Aksi</th>
                    @endcan
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
                            @can('admin')
                                <td>
                                    @if ($allow === true)
                                        <button class="btn btn-danger"
                                            wire:click="deleteConfirmation({{ $s->id }})"><i
                                                class="bx bx-trash me-1"></i>
                                            Delete</button>
                                    @else
                                        <button class="btn btn-danger disabled"><i class="bx bx-trash me-1"></i>
                                            Delete</button>
                                    @endif
                                </td>
                            @endcan
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
        });
        window.addEventListener('close-modal-lulus', event => {
            $('#modalLulus').modal('hide')
        });
        window.addEventListener('close-modal-delete', event => {
            $('#deleteModal').modal('hide')
        })
        window.addEventListener('show-delete-confirmation-modal', event => {
            $('#deleteModal').modal('show')
        });
    </script>
</div>
