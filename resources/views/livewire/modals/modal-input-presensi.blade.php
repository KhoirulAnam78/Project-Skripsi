<!-- Modal Input-->
<div class="modal fade" id="inputModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Input Presensi Pembelajaran</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" wire:model="tanggal" id="tanggal" class="form-control" />
                            @error('tanggal')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                            <input type="time" id="waktu_mulai" class="form-control" wire:model="waktu_mulai" />
                            @error('waktu_mulai')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mb-0">
                            <label for="waktu_berakhir" class="form-label">Waktu Berakhir</label>
                            <input type="time" wire:model="waktu_berakhir" id="waktu_berakhir"
                                class="form-control" />
                            @error('waktu_berakhir')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="topik" class="form-label">Topik/Agenda Pembelajaran</label>
                            <textarea name="topik" wire:model="topik" id="topik" class="form-control"></textarea>
                            @error('topik')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="table-responsive text-nowrap mx-3 mb-3">
                        <table class="table table-striped" id="examplei">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Presensi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @php
                                    $i = 0;
                                @endphp
                                @if ($siswa === null)
                                    <tr>
                                        <td colspan='7' align="center"><span>Tidak ada data</span></td>
                                    </tr>
                                @else
                                    @foreach ($siswa as $s)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $s->nama }}</td>
                                            <td>
                                                <input type="radio" id="presensi.{{ $s->id }}"
                                                    name="presensi.{{ $s->id }}" value='hadir'
                                                    wire:model="presensi.{{ $s->id }}">
                                                H
                                                <input type="radio" id="presensi.{{ $s->id }}"
                                                    name="presensi.{{ $s->id }}" value='izin'
                                                    wire:model="presensi.{{ $s->id }}">
                                                I
                                                <input type="radio" id="presensi.{{ $s->id }}"
                                                    name="presensi.{{ $s->id }}" value='alfa'
                                                    wire:model="presensi.{{ $s->id }}">
                                                A
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>

                        </table>
                        {{-- @if ($dataSiswa !== null)
                            {{ $dataSiswa->links() }}
                        @endif --}}
                    </div>
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
