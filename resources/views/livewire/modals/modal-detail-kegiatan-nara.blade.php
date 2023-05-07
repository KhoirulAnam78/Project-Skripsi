<div class="modal fade" id="showModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Lihat Siswa Tidak Hadir</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="table-responsive text-nowrap mb-3">
                        <table class="table table-striped" id="examplei">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kelas</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Presensi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @if (count($detail) === 0)
                                    <tr>
                                        <td colspan='7' align="center"><span>Tidak ada data</span></td>
                                    </tr>
                                @else
                                    @php
                                        $a = 0;
                                    @endphp
                                    @foreach ($detail as $s)
                                        <tr>
                                            <td>{{ ++$a }}
                                            </td>
                                            <td>{{ $s->siswa->kelas->where('tahun_akademik_id', $filterTahunAkademik)->first()->nama }}
                                            </td>
                                            <td>{{ $s->siswa->nisn }}</td>
                                            <td>{{ $s->siswa->nama }}</td>
                                            <td>
                                                {{ ucwords($s->status) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" wire:click="empty()"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
