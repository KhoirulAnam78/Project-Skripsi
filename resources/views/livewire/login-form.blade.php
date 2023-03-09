<div>
    <div class="container-xxl bg-primary">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center" style="margin: 0px;padding:0px">
                            <img src="{{ url('') }}/assets/assets/img/icons/brands/sman-titian-teras.jpg"
                                alt="Logo SMAN TITIAN TERAS.jpg" width="30%">
                            <br>
                            {{-- <span class="text-body fw-bolder">SMAN TITIAN TERAS</span> --}}
                        </div>
                        <!-- /Logo -->
                        <div align="center">
                            <span align="center" class="app-brand-text demo menu-text fw-bolder ms-2"
                                style="background-color: #0402FC; font-weight : bold;
        background-image: linear-gradient(45deg,#0402FC, #FC0204,#FCFE04);
        background-size: 100%; background-repeat: repeat;-webkit-background-clip: text;
        -webkit-text-fill-color: transparent; 
        -moz-background-clip: text;
        -moz-text-fill-color: transparent;">
                                Simonev</span>

                        </div>
                        {{-- <h4 class="my-2" align='center'
                            style="background-color: #0402FC; font-weight : bold;
                        background-image: linear-gradient(45deg,#0402FC, #FC0204,#FCFE04);
                        background-size: 100%; background-repeat: repeat;-webkit-background-clip: text;
                        -webkit-text-fill-color: transparent; 
                        -moz-background-clip: text;
                        -moz-text-fill-color: transparent;">
                            Simonev</h4> --}}
                        <h6 align="center" class="my-2">Sistem Informasi Monitoring Pembelajaran</h6>
                        <h5 align='center'>SMAN TITIAN TERAS</h5>
                        {{-- <p class="mb-2">Silahkan login terlebih dahulu</p> --}}
                        @if (session()->has('loginError'))
                            <div class="mb-2">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('loginError') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                        @endif

                        <form id="formAuthentication" class="mb-3" wire:submit.prevent="authenticate">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" wire:model="username"
                                    placeholder="Masukkan username" autofocus />
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
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>


    <!-- Modal Input-->
    <div class="modal fade" id="roleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="exampleModalLabel1">Pilih Role Terlebih Dahulu</h5>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <button class="col-12 btn btn-primary mb-3" wire:click="login('pimpinan')">Login Sebagai
                            Pimpinan</button>
                        <button class="col-12 btn btn-primary mb-3" wire:click="login('guru')">Login Sebagai
                            Guru</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- / Content -->
    <script>
        window.addEventListener('role-modal', event => {
            $('#roleModal').modal('show');
        });
    </script>

</div>
