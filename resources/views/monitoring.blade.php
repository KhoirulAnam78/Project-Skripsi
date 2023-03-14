@extends('layout.main2')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Monitoring pembelajaran hari ini
            {{ \Carbon\Carbon::now()->translatedFormat('l') . ', ' . date('d-m-Y') }}</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header px-3">Informasi pembelajaran
                    </h5>
                    <div class="card-b">
                        <div class="row mx-2 mb-3">
                            <div class="col-lg-3 col-md-3">
                                <label for="Kegiatan" class="form-label">Kegiatan</label>
                                <select wire:model="filterKegiatan" id="Kegiatan" class="form-select">
                                    <option value="Pembelajaran">Pembelajaran</option>

                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <label for="Kelas" class="form-label">Kelas</label>
                                <select wire:model="filterKelas" id="Kelas" class="form-select">
                                    <option value="1">X IPA 1</option>

                                </select>
                            </div>
                        </div>
                        <div class="row mx-3">
                            <div class="table-responsive text-nowrap mb-3">
                                <table class="table table-striped align-top" id="example">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Waktu</th>
                                            <th>Guru</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Topik</th>
                                            <th>Presensi</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody class="table-border-bottom-0">
                                  @if (count($dataSiswa) === 0)
                                      <tr>
                                          <td colspan='9' align="center"><span>Tidak ada data</span></td>
                                      </tr>
                                  @else
                                      @foreach ($dataSiswa as $s)
                                          <tr>
                                              <td>{{ ($dataSiswa->currentpage() - 1) * $dataSiswa->perpage() + $loop->index + 1 }}
                                              </td>
                                              <td>{{ $s->nisn }}</td>
                                              <td>{{ $s->nama }}</td>
                                              <td>{{ count($s->kehadiranPembelajarans->where('status', 'hadir')) }}
                                              </td>
                                              <td>{{ count($s->kehadiranPembelajarans->where('status', 'izin')) }}
                                              </td>
                                              <td>{{ count($s->kehadiranPembelajarans->where('status', 'sakit')) }}
                                              </td>
                                              <td>{{ count($s->kehadiranPembelajarans->where('status', 'alfa')) }}
                                              </td>
                                              <td>
                                                  {{ count($s->kehadiranPembelajarans->where('status', 'dinas dalam')) }}
                                              </td>
                                              <td>
                                                  {{ count($s->kehadiranPembelajarans->where('status', 'dinas luar')) }}
                                              </td>
                                          </tr>
                                      @endforeach
                                  @endif
          
                              </tbody> --}}

                                </table>
                                {{-- @if (count($dataSiswa) !== 0)
                              {{ $dataSiswa->links() }}
                          @endif --}}
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
