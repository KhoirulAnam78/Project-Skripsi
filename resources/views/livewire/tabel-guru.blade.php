<div>
    <div class="mx-3">
        <a href="" data-bs-toggle="modal" data-bs-target="#inputModal" class="btn btn-primary active mb-2 "><i
                class='bx bx-add-to-queue'></i> Tambah</a>
        <a href="" class="btn btn-success active mb-2"><i class='bx bxs-file-import'></i> Import</a>
        <a href="" class="btn btn-success active mb-2 float-right"><i class='bx bxs-file-export'></i>
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
    <div class="table-responsive text-nowrap m-3">
        <table class="table table-striped" id="example">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Kode Guru</th>
                    <th>Nama</th>
                    <th>No Telp</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php
                    $i = 0;
                @endphp
                @foreach ($guru as $g)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $g->nip }}</td>
                        <td>{{ $g->kode_guru }}</td>
                        <td>{{ $g->nama }}</td>
                        <td>{{ $g->no_telp }}</td>
                        <td>
                            @if ($g->status === 'aktif')
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
                                    <a class="dropdown-item" wire:click="editGuru({{ $g->id }})"><i
                                            class="bx bx-edit-alt me-1"></i>
                                        Edit</a>
                                    <a class="dropdown-item" wire:click="deleteGuru({{ $g->id }})"><i
                                            class="bx bx-trash me-1"></i>
                                        Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Input-->
    <div class="modal fade" id="inputModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="exampleModalLabel1">Tambah Data Guru</h5>
                    <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">

                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" wire:model="nip" id="nip" class="form-control"
                                    placeholder="123456789098765432" />
                                @error('nip')
                                    <span class="error"style="font-size:10px">* {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col mb-0">
                                <label for="kode_guru" class="form-label">Kode Guru</label>
                                <input type="text" wire:model="kode_guru" id="kode_guru" class="form-control"
                                    placeholder="KA" onkeyup="this.value = this.value.toUpperCase();" />
                                @error('kode_guru')
                                    <span class="error"style="font-size:10px">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" id="nama" class="form-control" placeholder="Khoirul Anam"
                                    wire:model="nama" />
                                @error('nama')
                                    <span class="error"style="font-size:10px">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col mb-0">
                                <label for="no_telp" class="form-label">No Telp</label>
                                <input type="text" wire:model="no_telp" id="no_telp" class="form-control"
                                    placeholder="08 atau +628" />
                                @error('no_telp')
                                    <span class="error"style="font-size:10px">* {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col mb-0">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" wire:model.defer="status" id="status" class="form-select">
                                    <option value="">Status</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak aktif">Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <span class="error"style="font-size:10px">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" wire:model="username" id="username" class="form-control"
                                    placeholder="khoirul1234" value="{{ $nip }}" />
                                @error('username')
                                    <span class="error"style="font-size:10px">* {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col mb-0 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" wire:model="password" class="form-control"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <span class="error"style="font-size:10px">* {{ $message }}</span>
                                @enderror
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

    {{-- MODAL EDIT --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="exampleModalLabel1">Edit Data Guru</h5>
                    <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="update">
                    <div class="modal-body">

                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" wire:model="nip" id="nip" class="form-control"
                                    placeholder="123456789098765432" />
                                @error('nip')
                                    <span class="error" style="font-size: 10px"style="font-size:10px">*
                                        {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col mb-0">
                                <label for="kode_guru" class="form-label">Kode Guru</label>
                                <input type="text" wire:model="kode_guru" id="kode_guru" class="form-control"
                                    placeholder="KA" onkeyup="this.value = this.value.toUpperCase();" />
                                @error('kode_guru')
                                    <span class="error"style="font-size:10px">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" id="nama" class="form-control" placeholder="Khoirul Anam"
                                    wire:model="nama" />
                                @error('nama')
                                    <span class="error"style="font-size:10px">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col mb-0">
                                <label for="no_telp" class="form-label">No Telp</label>
                                <input type="text" wire:model="no_telp" id="no_telp" class="form-control"
                                    placeholder="08 atau +628" />
                                @error('no_telp')
                                    <span class="error"style="font-size:10px">* {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col mb-0">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" wire:model.defer="status" id="status" class="form-select">
                                    <option value="">Status</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak aktif">Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <span class="error"style="font-size:10px">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="row g-2">
                            <div class="col mb-0">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" wire:model="username" id="username" class="form-control"
                                    placeholder="khoirul1234" value="{{ $nip }}" />
                                @error('username')
                                    <span class="error"style="font-size:10px">* {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col mb-0 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" wire:model="password" class="form-control"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <span class="error"style="font-size:10px">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div> --}}
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
</div>

<script>
    window.addEventListener('close-modal', event => {
        $('#inputModal').modal('hide')
    });
    window.addEventListener('close-edit-modal', event => {
        $('#editModal').modal('hide')
    })
    window.addEventListener('show-edit-modal', event => {
        $('#editModal').modal('show')
    });
</script>
