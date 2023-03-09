<div>
    <div class="mx-3">
        <a href="" data-bs-toggle="modal" data-bs-target="#inputModal" class="btn btn-primary active mb-2 "><i
                class='bx bx-add-to-queue'></i> Tambah</a>
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

    <div class="col-lg-4 col-md-4 mb-3 mx-3">
        <input type="text" wire:model="search" id="no_telp" class="form-control"
            placeholder="Cari berdasarkan nama tahun akademik" />
    </div>
    <div class="table-responsive text-nowrap mx-3 mb-3">
        <table class="table table-striped" id="examplei">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Tahun Akademik</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Berakhir</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @if (count($tahun_akademik) === 0)
                    <tr>
                        <td colspan='7' align="center"><span>Tidak ada data</span></td>
                    </tr>
                @else
                    @foreach ($tahun_akademik as $ta)
                        <tr>
                            <td>{{ ($tahun_akademik->currentpage() - 1) * $tahun_akademik->perpage() + $loop->index + 1 }}
                            </td>
                            <td>{{ $ta->nama }}</td>
                            <td>{{ $ta->tgl_mulai }}</td>
                            <td>{{ $ta->tgl_berakhir }}</td>
                            <td>
                                @if ($ta->status === 'aktif')
                                    <span class="badge bg-label-info me-1">Aktif</span>
                                @else
                                    <span class="badge bg-label-danger me-1">Tidak Aktif</span>
                                @endif
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" wire:click="edit({{ $ta->id }})"><i
                                                class="bx bx-edit-alt me-1"></i>
                                            Edit</a>
                                        <a class="dropdown-item" wire:click="deleteConfirmation({{ $ta->id }})"><i
                                                class="bx bx-trash me-1"></i>
                                            Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif

            </tbody>

        </table>

        {{ $tahun_akademik->links() }}
    </div>
    @include('livewire.modals.modal-tahun-akademik')
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
    </script>
</div>
