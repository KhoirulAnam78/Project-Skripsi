<div>
    <div class="mx-3">
        @can('admin')
            <a href="" data-bs-toggle="modal" style="background-color : #1052BA;border-color: #1052BA"
                data-bs-target="#inputModal" class="btn btn-primary active mb-2 "><i class='bx bx-add-to-queue'></i> Tambah</a>
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
    <div class="table-responsive text-nowrap mx-3 mb-3">
        <table class="table table-striped" id="example">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Angkatan Ke-</th>
                    <th>Wali Asrama</th>
                    <th>Status</th>
                    @can('admin')
                        <th>Aksi</th>
                    @endcan
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @if (count($angkatan) === 0)
                    <tr>
                        <td colspan='7' align="center"><span>Tidak ada data</span></td>
                    </tr>
                @else
                    @foreach ($angkatan as $a)
                        <tr>
                            <td>{{ ($angkatan->currentpage() - 1) * $angkatan->perpage() + $loop->index + 1 }}</td>
                            <td>{{ $a->nama }}</td>
                            <td>
                                <ul>
                                    @foreach ($a->waliAsramas as $w)
                                        <li>{{ $w->nama }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <span class="badge bg-label-info me-1">{{ $a->status }}</span>
                            </td>

                            @can('admin')
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" wire:click="edit({{ $a->id }})"><i
                                                    class="bx bx-edit-alt me-1"></i>
                                                Edit</a>
                                            <a class="dropdown-item" wire:click="deleteConfirmation({{ $a->id }})"><i
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

        {{ $angkatan->links() }}
    </div>
    @include('livewire.modals.modal-angkatan')
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
