<!-- Modal Input-->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel1">Edit Profile</h5>
                <button type="button" class="btn-close" wire:click="empty()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="edit">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" wire:model="username" id="username" class="form-control" />
                            @error('username')
                                <span class="error" style="font-size:12px; font-style:italic">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if (Auth::user()->role !== 'admin' and Auth::user()->role !== 'siswa')
                        <div class="row">
                            <div class="col mb-3">
                                <label for="no_telp" class="form-label">Nomor Telepon</label>
                                <input type="text" id="no_telp" class="form-control" placeholder=""
                                    wire:model="no_telp" />
                                @error('no_telp')
                                    <span class="error" style="font-size:12px; font-style:italic">*
                                        {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col mb-0 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Ubah Password <br> <span
                                        style="font-size: 10px">(kosongkan jika tidak ingin
                                        diubah)</span></label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" wire:model="password" class="form-control"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password')
                                <span class="error" style="font-size:12px; font-style:italic">*
                                    {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        wire:click="empty()"data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
