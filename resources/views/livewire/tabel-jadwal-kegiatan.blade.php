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
        @can('admin')
            @if ($allow !== false)
                <a href="" data-bs-toggle="modal" data-bs-target="#inputModal"
                    class="btn btn-primary active mb-2 {{ $allow === false ? 'disabled' : '' }}"
                    style="background-color : #1052BA;border-color: #1052BA"><i class='bx bx-add-to-queue'></i> Tambah</a>
                <a href="" class="btn btn-success active mb-2 {{ $allow === false ? 'disabled' : '' }}"
                    data-bs-toggle="modal" data-bs-target="#importModal"
                    style="background-color: #5CB85C;border-color: #5CB85C"><i class='bx bxs-file-import'></i>
                    Import</a>
            @else
                <a href="" class="btn btn-primary active mb-2 disabled"><i class='bx bx-add-to-queue'></i> Tambah</a>
                <a href="" class="btn btn-success active mb-2 disabled"
                    style="background-color: #5CB85C;border-color: #5CB85C"><i class='bx bxs-file-import'></i>
                    Import</a>
            @endif
        @endcan
        {{--         
        @can('admin')
            <a href="" data-bs-toggle="modal" data-bs-target="#inputModal" class="btn btn-primary active mb-2 "
                style="background-color : #1052BA;border-color: #1052BA"><i class='bx bx-add-to-queue'></i> Tambah</a>
            <a href="" class="btn btn-success active mb-2" data-bs-toggle="modal" data-bs-target="#importModal"
                style="background-color: #5CB85C;border-color: #5CB85C"><i class='bx bxs-file-import'></i>
                Import</a>
        @endcan --}}

        <a class="btn btn-info mb-2 text-white" wire:click="export()"
            style="background-color: #F0AD4E;border-color: #F0AD4E"><i class='bx bxs-file-export'></i>
            Export</a>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 mb-3 mx-3">
            <label for="tahun_akademik_id" class="form-label">Tahun Akademik</label>
            <select wire:model="tahun_akademik_id" id="tahun_akademik_id" class="form-select">
                @foreach ($tahunAkademik as $ta)
                    <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-3 mb-2 mx-3">
            <label for="search" class="form-label">Angkatan</label>
            <select wire:model="filterAngkatan" id="filterAngkatan" class="form-select">
                @if (count($angkatan) !== 0)
                    @foreach ($angkatan as $a)
                        <option value="{{ $a->id }}">{{ $a->nama }}</option>
                    @endforeach
                @else
                    <option value="">Tidak ada angkatan</option>
                @endif
            </select>
        </div>
    </div>
    <div class="table-responsive text-nowrap mx-3 mb-3">
        <table class="table table-striped" id="examplei">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Hari</th>
                    <th>Kegiatan</th>
                    <th>Angkatan</th>
                    <th>Waktu Kegiatan</th>
                    @can('admin')
                        <th>Aksi</th>
                    @endcan
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @if (count($jadwalKegiatan) === 0)
                    <tr>
                        <td colspan='7' align="center"><span>Tidak ada data</span></td>
                    </tr>
                @else
                    @foreach ($jadwalKegiatan as $j)
                        <tr>
                            <td>{{ ($jadwalKegiatan->currentpage() - 1) * $jadwalKegiatan->perpage() + $loop->index + 1 }}
                            </td>
                            <td>{{ $j->hari }}</td>
                            <td>{{ $j->kegiatan->nama }}</td>
                            <td>{{ $j->angkatan->nama }}</td>
                            <td>{{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }}</td>
                            @can('admin')
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" wire:click="edit({{ $j->id }})"><i
                                                    class="bx bx-edit-alt me-1"></i>
                                                Edit</a>
                                            <a class="dropdown-item"
                                                wire:click="deleteConfirmation({{ $j->id }})"><i
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
        @if (count($jadwalKegiatan) !== 0)
            {{ $jadwalKegiatan->links() }}
        @endif
    </div>
    @include('livewire.modals.modal-jadwal-kegiatan')
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
