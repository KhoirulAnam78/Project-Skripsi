<div class="modal fade" id="showModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Lihat Presensi Pertemuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="closeModal()"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea disabled name="keterangan" wire:model="keterangan" id="keterangan" class="form-control"></textarea>
                            @error('keterangan')
                                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="table-responsive text-nowrap mb-3">
                        <table class="table table-striped" id="examplei">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Presensi</th>
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
                                            <td>{{ ($siswa->currentpage() - 1) * $siswa->perpage() + $loop->index + 1 }}
                                            </td>
                                            <td>{{ $s->nisn }}</td>
                                            <td>{{ $s->nama }}</td>
                                            <td>
                                                <input type="text" class="form-control" disabled id="presensihadir"
                                                    name="presensi.{{ $s->id }}"
                                                    wire:model="presensi.{{ $s->id }}">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" wire:click="closeModal()"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
