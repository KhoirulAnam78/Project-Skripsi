<table class="table table-striped" id="examplei">
    <thead>
        <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Kode Guru</th>
            <th>Nama</th>
            <th>No Telp</th>
            <th>Status</th>
            @if ($show === true)
                <th>Actions</th>
            @endif
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">
        @php
            $i = 0;
        @endphp
        @if (count($guru) === 0)
            <tr>
                <td colspan='7' align="center"><span>Tidak ada data</span></td>
            </tr>
        @else
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
                        @if ($show === true)
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" wire:click="editGuru({{ $g->id }})"><i
                                        class="bx bx-edit-alt me-1"></i>
                                    Edit</a>
                                <a class="dropdown-item" wire:click="deleteConfirmation({{ $g->id }})"><i
                                        class="bx bx-trash me-1"></i>
                                    Delete</a>
                            </div>
                        </div>
                    </td>
            @endif

            </tr>
        @endforeach
        @endif

    </tbody>

</table>
