<div>
    <div class="mx-3">
        @can('admin')
            <a href="" data-bs-toggle="modal" data-bs-target="#inputModal" class="btn btn-primary active mb-2 "
                style="background-color : #1052BA;border-color: #1052BA"><i class='bx bx-add-to-queue'></i> Tambah</a>
            <a href="" class="btn btn-success active mb-2" data-bs-toggle="modal" data-bs-target="#importModal"
                style="background-color: #5CB85C;border-color: #5CB85C"><i class='bx bxs-file-import'></i>
                Import</a>
        @endcan
        <a class="btn btn-info mb-2 text-white" wire:click="export()"
            style="background-color: #F0AD4E;border-color: #F0AD4E"><i class='bx bxs-file-export'></i>
            Export</a>
    </div>
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
    <div class="row justify-content-between">
        <div class="col-lg-4 col-md-4 mb-3 mx-3">
            <label for="search" class="form-label">Pencarian</label>
            <input type="text" wire:model="search" id="search" class="form-control"
                placeholder="Cari berdasarkan nama siswa" />
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-2 mx-3">
            <div wire:loading.delay
                class="z-50 static flex fixed left-0 top-0 bottom-0 w-full bg-gray-400 bg-opacity-50">
                <img src="https://paladins-draft.com/img/circle_loading.gif" width="50" height="50"
                    class="m-auto mt-1/4"> <span>Loading ...</span>
            </div>
        </div>
    </div>
    <div class="table-responsive text-nowrap mx-3 mb-3">
        <table class="table table-striped" id="examplei">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>No Telp</th>
                    <th>Status</th>
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
                    @foreach ($siswa as $g)
                        <tr>
                            <td>{{ ($siswa->currentpage() - 1) * $siswa->perpage() + $loop->index + 1 }}</td>
                            <td>{{ $g->nisn }}</td>
                            <td>{{ $g->nama }}</td>
                            <td>{{ $g->no_telp }}</td>
                            <td>
                                <span class="badge bg-label-info me-1">{{ $g->status }}</span>
                            </td>
                            @can('admin')
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" wire:click="editSiswa({{ $g->id }})"><i
                                                    class="bx bx-edit-alt me-1"></i>
                                                Edit</a>
                                            <a class="dropdown-item"
                                                wire:click="deleteConfirmation({{ $g->id }})"><i
                                                    class="bx bx-trash me-1"></i>
                                                Delete</a>
                                        </div>
                                    </div>
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
    @include('livewire.modals.modal-siswa')
    <script>
        window.addEventListener('close-modal', event => {
            $('#inputModal').modal('hide');
        });

        window.addEventListener('close-edit-modal', event => {
            $('#editModal').modal('hide');
        })
        window.addEventListener('close-modal-import', event => {
            $('#importModal').modal('hide')
        })
        window.addEventListener('close-modal-delete', event => {
            $('#deleteModal').modal('hide')
        })
        window.addEventListener('show-edit-modal', event => {
            $('#editModal').modal('show');
        });
        window.addEventListener('show-delete-confirmation-modal', event => {
            $('#deleteModal').modal('show')
        });
    </script>
</div>
