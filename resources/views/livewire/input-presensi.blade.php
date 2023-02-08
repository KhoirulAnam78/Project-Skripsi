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
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            @else
                <option>Tidak ada kelas</option>
            @endif
        </select>
    </div>
    <div class="row mx-2 mb-3">
        @if ($mapel !== null)
            @foreach ($mapel as $m)
                <div class="col-lg-4 col-md-4 col-xl-4">
                    <button data-bs-toggle="modal" data-bs-target="#inputModal"
                        wire:click="inputPresensi({{ $m->id }})" class="btn btn-primary">
                        <h5 class="card-title text-white">{{ $m->kelas->nama }}</h5>
                        <h6 class="card-title text-white">Guru : {{ $m->guru->nama }}</h6>
                        <h5 class="card-title text-white">{{ $m->mataPelajaran->nama }}</h5>
                        <h6 class="card-title text-white">Jam pelajaran
                            {{ substr($m->waktu_mulai, 0, -3) . '-' . substr($m->waktu_berakhir, 0, -3) }}</h6>
                    </button>
                </div>
            @endforeach
        @endif
    </div>
    @include('livewire.modals.modal-input-presensi')
    <script>
        window.addEventListener('close-input-modal', event => {
            $('#InputModal').modal('hide')
        })
        window.addEventListener('show-input-modal', event => {
            $('#inputModal').modal('show');
        });
    </script>
</div>
