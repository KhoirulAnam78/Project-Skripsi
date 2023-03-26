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
            <a href="" data-bs-toggle="modal" data-bs-target="#inputModal" class="btn btn-primary active mb-2"><i
                    class='bx bx-add-to-queue'></i> Tambah</a>
            <a href="" class="btn btn-success active mb-2" data-bs-toggle="modal" data-bs-target="#importModal"
                style="background-color: rgb(0, 185, 0);border-color: rgb(0, 185, 0)"><i class='bx bxs-file-import'></i>
                Import</a>
        @endcan
        <a class="btn btn-info mb-2 text-white" wire:click="export()"
            style="background-color: rgb(0, 143, 0);border-color: rgb(0, 143, 0)"><i class='bx bxs-file-export'></i>
            Export</a>
    </div>
    <div class="col-lg-4 col-md-4 mb-2 mx-3">
        <label for="search" class="form-label">Pencarian</label>
        <input type="text" wire:model="search" id="search" class="form-control"
            placeholder="Cari berdasarkan nama guru" />
    </div>
    <div class="col-lg-2 col-md-2 mx-3">
        <div wire:loading.delay class="z-50 static flex fixed left-0 top-0 bottom-0 w-full bg-gray-400 bg-opacity-50">
            <img src="https://paladins-draft.com/img/circle_loading.gif" width="50" height="50"
                class="m-auto mt-1/4"> <span>Loading ...</span>
        </div>
    </div>
    <div class="table-responsive text-nowrap mx-3 mb-3">
        <table class="table table-striped" id="examplei">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Guru</th>
                    <th>Kode Guru</th>
                    <th>Hari</th>
                    <th>Jam Piket</th>
                    @can('admin')
                        <th>Aksi</th>
                    @endcan
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @if (count($jadwalPiket) === 0)
                    <tr>
                        <td colspan='7' align="center"><span>Tidak ada data</span></td>
                    </tr>
                @else
                    @foreach ($jadwalPiket as $j)
                        {{-- <tr>
                            <td>
                                {{ ($jadwalPiket->currentpage() - 1) * $jadwalPiket->perpage() + $loop->index + 1 }}
                            </td>
                            <td>
                                {{ $j->nama }}</td>
                            <td>
                                {{ $j->kode_guru }}</td>
                            <td>{{ $j->jadwalGuruPikets->first()->hari }}</td>
                            <td>
                                {{ substr($j->jadwalGuruPikets->first()->waktu_mulai, 0, -3) . '-' . substr($j->jadwalGuruPikets->first()->waktu_berakhir, 0, -3) }}
                            </td>
                            @can('admin')
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                wire:click="edit({{ $j->jadwalGuruPikets->first()->id }})"><i
                                                    class="bx bx-edit-alt me-1"></i>
                                                Edit</a>
                                            <a class="dropdown-item"
                                                wire:click="deleteConfirmation({{ $j->jadwalGuruPikets->first()->id }})"><i
                                                    class="bx bx-trash me-1"></i>
                                                Delete</a>
                                        </div>
                                    </div>
                                </td>
                            @endcan
                        </tr> --}}

                        @if (count($j->jadwalGuruPikets->groupBy('guru_id')) !== 0)
                            @php
                                $b = $j->jadwalGuruPikets->groupBy('guru_id')->first();
                                $rowCount = count($j->jadwalGuruPikets->groupBy('guru_id'));
                            @endphp
                            <tr>
                                <td
                                    {{ count($j->jadwalGuruPikets->groupBy('guru_id')) !== 1 ? 'rowspan=' . $rowCount : '' }}>
                                    {{ ($jadwalPiket->currentpage() - 1) * $jadwalPiket->perpage() + $loop->index + 1 }}
                                </td>
                                <td
                                    {{ count($j->jadwalGuruPikets->groupBy('guru_id')) !== 1 ? 'rowspan=' . $rowCount : '' }}>
                                    {{ $j->nama }}</td>
                                <td
                                    {{ count($j->jadwalGuruPikets->groupBy('guru_id')) !== 1 ? 'rowspan=' . $rowCount : '' }}>
                                    {{ $j->kode_guru }}</td>
                                <td>{{ $b->first()->hari }}</td>
                                <td>
                                    {{ substr($b->first()->waktu_mulai, 0, -3) . '-' . substr($b->first()->waktu_berakhir, 0, -3) }}
                                </td>
                                @can('admin')
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" wire:click="edit({{ $b->first()->id }})"><i
                                                        class="bx bx-edit-alt me-1"></i>
                                                    Edit</a>
                                                <a class="dropdown-item"
                                                    wire:click="deleteConfirmation({{ $b->first()->id }})"><i
                                                        class="bx bx-trash me-1"></i>
                                                    Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                @endcan

                            </tr>
                            @foreach ($j->jadwalGuruPikets->groupBy('guru_id') as $key => $b)
                                @foreach ($b as $k)
                                    @if ($loop->first)
                                        @continue
                                    @endif
                                    {{-- @dump(count($b)) --}}
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $k->hari }}</td>
                                        <td>
                                            {{ substr($k->waktu_mulai, 0, -3) . '-' . substr($k->waktu_berakhir, 0, -3) }}
                                        </td>
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
                                                        <a class="dropdown-item"
                                                            wire:click="deleteConfirmation({{ $k->id }})"><i
                                                                class="bx bx-trash me-1"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endcan

                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                    @endforeach
                @endif

            </tbody>

        </table>
        @if (count($jadwalPiket) !== 0)
            {{ $jadwalPiket->links() }}
        @endif
    </div>
    @include('livewire.modals.modal-jadwal-piket')
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
