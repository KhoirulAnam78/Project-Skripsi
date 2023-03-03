<div>
    <form id="formAuthentication" class="mb-3" wire:submit.prevent="authenticate">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" wire:model="username" placeholder="Masukkan username" autofocus />
            @error('username')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 form-password-toggle">
            <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
                {{-- <a href="auth-forgot-password-basic.html">
                    <small>Forgot Password?</small>
                </a> --}}
            </div>
            <div class="input-group input-group-merge">
                <input type="password" wire:model="password" class="form-control"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        {{-- <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember-me" />
                <label class="form-check-label" for="remember-me"> Remember Me </label>
            </div>
        </div> --}}
        <div class="mb-3">
            <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
        </div>
    </form>
</div>
