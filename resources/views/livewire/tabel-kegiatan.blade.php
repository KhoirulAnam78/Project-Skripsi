<div>
    <div class="mx-3">
        @can('admin')
            <a href="" data-bs-toggle="modal" data-bs-target="#inputModal"
                style="background-color : #1052BA;border-color: #1052BA" class="btn btn-primary active mb-2 "><i
                    class='bx bx-add-to-queue'></i> Tambah</a>
        @endcan
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
    <div class="row justify-content-between">
        <div class="col-lg-4 col-md-4 mb-3 mx-3">
            <label for="search" class="form-label">Pencarian</label>
            <input type="text" wire:model="search" id="search" class="form-control"
                placeholder="Cari berdasarkan nama kegiatan" />
        </div>
    </div>

    <div class="table-responsive text-nowrap mx-3 mb-3">
        <table class="table table-striped" id="example1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Narasumber</th>
                    @can('admin')
                        <th>Aksi</th>
                    @endcan
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @if (count($kegiatan) === 0)
                    <tr>
                        <td colspan='7' align="center"><span>Tidak ada data</span></td>
                    </tr>
                @else
                    @foreach ($kegiatan as $k)
                        <tr>
                            <td>{{ ($kegiatan->currentpage() - 1) * $kegiatan->perpage() + $loop->index + 1 }}</td>
                            <td>{{ $k->nama }}</td>
                            <td>{{ $k->narasumber == true ? 'Ya' : 'Tidak' }}</td>
                            @can('admin')
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" wire:click="edit({{ $k->id }})"><i
                                                    class="bx bx-edit-alt me-1"></i>
                                                Edit</a>
                                            <a class="dropdown-item" wire:click="deleteConfirmation({{ $k->id }})"><i
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

        {{ $kegiatan->links() }}
    </div>
    @include('livewire.modals.modal-kegiatan')
    <script>
        window.addEventListener('close-modal', event => {
            $('#inputModal').modal('hide');
        });
        window.addEventListener('close-edit-modal', event => {
            $('#editModal').modal('hide');
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
        window.addEventListener('close-modal-import', event => {
            $('#importModal').modal('hide')
        })
    </script>
</div>