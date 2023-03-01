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
    <div class="row mx-2 mb-3 justify-content-start">
        <div class="col-lg-2">
            <a class="btn btn-info mb-2 text-white" wire:click="export()"
                style="background-color: rgb(0, 143, 0);border-color: rgb(0, 143, 0)"><i class='bx bxs-file-export'></i>
                Export</a>
        </div>
    </div>
    <div class="row mx-2 mb-3">
        <div class="col-lg-4 col-md-4">
            <label for="tanggalAwal" class="form-label">Tanggal Awal</label>
            <input type="date" wire:model="tanggalAwal" name="tanggalAwal" id="tanggalAwal" class="form-control" />
            @error('tanggalAwal')
                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                    {{ $message }}</span>
            @enderror
        </div>
        <div class="col-lg-4 col-md-4">
            <label for="tanggalAkhir" class="form-label">Tanggal Akhir</label>
            <input type="date" wire:model="tanggalAkhir" name="tanggalAkhir" id="tanggalAkhir"
                class="form-control" />
            @error('tanggalAkhir')
                <span class="error" style="color:red; font-size:12px; font-style:italic">*
                    {{ $message }}</span>
            @enderror
        </div>
    </div>
    @can('admin')
        <div class="row mx-2 mb-3">
            <div class="col-lg-4 col-md-4 mb-0">
                <label for="search" class="form-label">Pencarian</label>
                <input type="text" wire:model="search" id="search" class="form-control"
                    placeholder="Cari berdasarkan nama guru" />
            </div>
        </div>
    @endcan
    <div class="row mx-3">
        <div class="table-responsive text-nowrap mb-3">
            @include('livewire.tables.table-rekap-guru')
            @if (count($guru) !== 0)
                {{ $guru->links() }}
            @endif
        </div>
    </div>
</div>
