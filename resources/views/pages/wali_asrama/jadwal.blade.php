@extends('layout.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Jadwal Kegiatan</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header px-3">Jadwal kegiatan angkatan {{ $angkatan }}
                    </h5>
                    <div class="card-body">
                        <div class="row row-bordered g-0">
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead>
                                        <tr class="table-primary">
                                            <th>Jam</th>
                                            <th>Hari</th>
                                            <th>Kegiatan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($jadwal as $j)
                                            <tr class="table-default">
                                                <td>{{ substr($j->waktu_mulai, 0, -3) . '-' . substr($j->waktu_berakhir, 0, -3) }}
                                                </td>
                                                <td>{{ $j->hari }}</td>
                                                <td>{{ $j->kegiatan->nama }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Total Revenue -->
@endsection
