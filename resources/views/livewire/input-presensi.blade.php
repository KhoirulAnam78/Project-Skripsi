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
    <div class="col-lg-3 col-md-3 mb-4 mx-3">
        <label for="kelas_id" class="form-label">Kelas</label>
        <select wire:model="filterKelas" id="kelas_id" class="form-select">
            @if ($kelas !== null)
                <option value="">Pilih</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            @else
                <option>Pilih</option>
            @endif
        </select>
    </div>
    <div class="row mx-2 mb-3">
        @if ($mapel !== null)
            @foreach ($mapel as $m)
                <div class="col-md-4 col-xl-4">
                    <a href="" class="btn btn-primary">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Guru : {{ $m->guru->nama }}</h6>
                                <h5 class="card-title">{{ $m->mataPelajaran->nama }}</h5>
                                <h6 class="card-title">Jam pelajaran
                                    {{ substr($m->waktu_mulai, 0, -3) . '-' . substr($m->waktu_berakhir, 0, -3) }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @endif
    </div>

</div>
