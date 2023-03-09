<!-- Navbar -->

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <span class="d-inline">
                        @php
                            if (Auth::user()->role === 'admin') {
                                echo 'Administrator';
                            } elseif (Auth::user()->role === 'guru') {
                                echo Auth::user()->guru->nama;
                            } elseif (Auth::user()->role === 'siswa') {
                                echo Auth::user()->siswa->nama;
                            } elseif (Auth::user()->role === 'pimpinan') {
                                echo Auth::user()->guru->nama;
                            } else {
                                echo 'User';
                            }
                        @endphp
                    </span>
                    <div class="avatar avatar-online d-inline">
                        <img src="{{ url('') }}/assets/assets/img/avatars/default-user.jpg" alt
                            class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    {{-- <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ url('') }}/assets/assets/img/avatars/1.png" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">John Doe</span>
                                    <small class="text-muted">Admin</small>
                                </div>
                            </div>
                        </a>
                    </li> --}}
                    {{-- <li>
                        <div class="dropdown-divider"></div>
                    </li> --}}
                    <li>
                        <a class="dropdown-item" href="/profile">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">Profil</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">Settings</span>
                        </a>
                    </li> --}}
                    {{-- <li>
                        <div class="dropdown-divider"></div>
                    </li> --}}
                    @if (Auth::user()->role === 'guru')
                        @if (Auth::user()->guru->pimpinan === 1)
                            <li>
                                <a class="dropdown-item" href="/login-pimpinan">
                                    <i class="bx bx-power-off me-2"></i>
                                    <span class="align-middle">Login Pimpinan</span>
                                </a>
                            </li>
                        @endif
                    @endif
                    @if (Auth::user()->role === 'pimpinan')
                        @if (Auth::user()->guru->pimpinan === 1)
                            <li>
                                <a class="dropdown-item" href="/login-guru">
                                    <i class="bx bx-power-off me-2"></i>
                                    <span class="align-middle">Login Guru</span>
                                </a>
                            </li>
                        @endif
                    @endif


                    <li>
                        <a class="dropdown-item" href="/logout">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>

<!-- / Navbar -->
