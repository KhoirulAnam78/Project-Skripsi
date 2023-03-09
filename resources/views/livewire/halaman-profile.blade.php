<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header px-3">Profile User
                </h5>
                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="row">
                            <div class="mb-2 mx-3">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-4 m-0">
                            <img src="{{ url('') }}/assets/assets/img/avatars/default-user.jpg"
                                class="card-img card-img-left" alt="">
                        </div>
                        <div class="col-md-8 m-0">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-striped">
                                    <tbody class="table-border-bottom-0">
                                        @if ($user->role === 'guru' or $user->role === 'pimpinan')
                                            <tr>
                                                <td>Username</td>
                                                <td>: {{ $user->username }}</td>
                                            </tr>
                                            <tr>
                                                <td>Nama</td>
                                                <td>
                                                    : {{ $user->guru->nama }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>NIP</td>
                                                <td>
                                                    : {{ $user->guru->nip }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Nomor Telepon</td>
                                                <td>: {{ $user->guru->no_telp }}</td>
                                            </tr>
                                        @endif
                                        @if ($user->role === 'siswa')
                                            <tr>
                                                <td>Username</td>
                                                <td>: {{ $user->username }}</td>
                                            </tr>
                                            <tr>
                                                <td>Nama</td>
                                                <td>
                                                    : {{ $user->siswa->nama }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>NISN</td>
                                                <td>
                                                    : {{ $user->siswa->nisn }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Nomor Telepon</td>
                                                <td>: {{ $user->siswa->no_telp }}</td>
                                            </tr>
                                        @endif
                                        @if ($user->role === 'admin')
                                            <tr>
                                                <td>Username</td>
                                                <td>: {{ $user->username }}</td>
                                            </tr>
                                            <tr>
                                                <td>Nama</td>
                                                <td>
                                                    : Administrator
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <button class="btn btn-info mt-3" data-bs-toggle="modal" data-bs-target="#editModal">Edit
                                Profile</button>
                        </div>
                    </div>
                </div>
            </div>
            @include('livewire.modals.modal-profile')

        </div>
    </div>
    <script>
        window.addEventListener('close-modal', event => {
            $('#editModal').modal('hide');
        });
    </script>
</div>
