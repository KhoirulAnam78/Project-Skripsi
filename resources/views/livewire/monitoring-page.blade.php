<div>
    <div class="card p-3">
        <div class="col-12">

            <div class="row mb-3">
                <div class="col-lg-3 col-md-3">
                    <label for="Kegiatan" class="form-label">Kegiatan</label>
                    <select disabled wire:model="filterKegiatan" id="Kegiatan" class="form-select">
                        <option value="pembelajaran">Pembelajaran</option>
                        {{-- <option value="kegiatan">Kegiatan Non Akademik</option> --}}
                    </select>
                </div>
                <livewire:pembelajaran-monitoring />
                {{-- @if ($filterKegiatan == 'pembelajaran')
                @else
                    <livewire:kegiatan-monitoring />
                @endif --}}
            </div>
        </div>
    </div>
</div>
