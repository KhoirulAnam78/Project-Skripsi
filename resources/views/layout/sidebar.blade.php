@php
    use App\Models\Kegiatan;
    $kegiatan = Kegiatan::all();
@endphp
<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/dashboard" class="app-brand-link">
            <div class="app-brand-logo demo">
                <img src="{{ url('') }}/assets/assets/img/icons/brands/sman-titian-teras.jpg"
                    alt="Logo SMAN TITIAN TERAS.jpg" width="40px">
            </div>
            <span class="app-brand-text demo menu-text fw-bolder ms-2"
                style="background-color: #0402FC; font-weight : bold;
            background-image: linear-gradient(45deg,#0402FC, #FC0204,#FCFE04);
            background-size: 100%; background-repeat: repeat;-webkit-background-clip: text;
            -webkit-text-fill-color: transparent; 
            -moz-background-clip: text;
            -moz-text-fill-color: transparent;">Simonev</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ $title === 'Dashboard' ? 'active' : '' }}">
            <a href="/dashboard" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        <!-- Layouts -->
        @can('admin')
            <li
                class="menu-item {{ ($title === 'Data Guru' or $title === 'Tahun Akademik' or $title === 'Data Kelas' or $title === 'Data Siswa' or $title === 'Mata Pelajaran' or $title === 'Data Narasumber' or $title === 'Data Angkatan' or $title === 'Data Wali Asrama' or $title === 'Data Kegiatan') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    {{-- <i class="menu-icon tf-icons bx bx-layout"></i> --}}
                    <i class='menu-icon tf-icons bx bx-data'></i>
                    <div>Data Master</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ $title === 'Data Guru' ? 'active' : '' }}">
                        <a href="/data-guru" class="menu-link">
                            <div data-i18n="Data Guru">Data Guru</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Data Wali Asrama' ? 'active' : '' }}">
                        <a href="/data-wali-asrama" class="menu-link">
                            <div data-i18n="Mata Pelajaran">Data Wali Asrama</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Tahun Akademik' ? 'active' : '' }}">
                        <a href="tahun-akademik" class="menu-link">
                            <div data-i18n="Tahun Akademik">Tahun Akademik</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Data Angkatan' ? 'active' : '' }}">
                        <a href="/data-angkatan" class="menu-link">
                            <div data-i18n="Mata Pelajaran">Data Angkatan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Data Kelas' ? 'active' : '' }}">
                        <a href="/kelas" class="menu-link">
                            <div data-i18n="Data Kelas">Data Kelas</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Data Siswa' ? 'active' : '' }}">
                        <a href="/siswa" class="menu-link">
                            <div data-i18n="Data Siswa">Data Siswa</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Mata Pelajaran' ? 'active' : '' }}">
                        <a href="/mata-pelajaran" class="menu-link">
                            <div data-i18n="Mata Pelajaran">Mata Pelajaran</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Data Kegiatan' ? 'active' : '' }}">
                        <a href="/data-kegiatan" class="menu-link">
                            <div data-i18n="Mata Pelajaran">Data Kegiatan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Data Narasumber' ? 'active' : '' }}">
                        <a href="/data-narasumber" class="menu-link">
                            <div data-i18n="Mata Pelajaran">Data Narasumber</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Operasional</span>
            </li>
            <li class="menu-item {{ $title === 'Rombongan Belajar' ? 'active' : '' }}">
                <a href="/rombongan-belajar" class="menu-link">
                    {{-- <i class='menu-icon bx bxs-group'></i> --}}
                    <i class='menu-icon bx bx-group'></i>
                    <div data-i18n="Rombongan Belajar">Rombongan Belajar</div>
                </a>
            </li>
            <li
                class="menu-item {{ ($title === 'Jadwal Pelajaran' or $title === 'Jadwal Guru Piket' or $title === 'Jadwal Pengganti' or $title === 'Jadwal Kegiatan') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-dock-top"></i>
                    <div data-i18n="Account Settings">Penjadwalan</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ $title === 'Jadwal Pelajaran' ? 'active' : '' }}">
                        <a href="/jadwal-pelajaran" class="menu-link">
                            <div data-i18n="Jadwal Pelajaran">Jadwal Pelajaran</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Jadwal Pengganti' ? 'active' : '' }}">
                        <a href="/jadwal-pengganti" class="menu-link">
                            <div data-i18n="Jadwal Pengganti">Jadwal Pengganti</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Jadwal Guru Piket' ? 'active' : '' }}">
                        <a href="/jadwal-guru-piket" class="menu-link">
                            <div data-i18n="Jadwal Guru Piket">Jadwal Guru Piket</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Jadwal Kegiatan' ? 'active' : '' }}">
                        <a href="/jadwal-kegiatan" class="menu-link">
                            <div data-i18n="Jadwal Kegiatan">Jadwal Kegiatan</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li
                class="menu-item {{ $title === 'Presensi Pembelajaran' ? 'active open' : '' }} @php
foreach ($kegiatan as $k) {
                    if ($k->nama === $title) {
                        echo 'active open';
                    }   
                } @endphp">
                {{-- <a href="javascript:void(0);" class="menu-link menu-toggle"> --}}
                {{-- <i class="menu-icon tf-icons bx bx-lock-open-alt"></i> --}}
                {{-- <i class='menu-icons tf-icons bx bxs-file-export'></i> --}}
                {{-- <i class='menu-icons bx bx-log-in-circle'></i> --}}
                {{-- <div data-i18n="Input Presensi">Input Presensi</div> --}}
                {{-- </a> --}}
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    {{-- <i class="menu-icon tf-icons bx bx-dock-top"></i> --}}
                    <i class='menu-icon tf-icons bx bx-log-in-circle'></i>
                    <div data-i18n="Account Settings">Input Presensi</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ $title === 'Presensi Pembelajaran' ? 'active' : '' }}">
                        <a href="/presensi-pembelajaran" class="menu-link">
                            <div data-i18n="Presensi Pembelajaran">Presensi Pembelajaran</div>
                        </a>
                    </li>
                    @foreach ($kegiatan as $k)
                        <li class="menu-item {{ $title === $k->nama ? 'active' : '' }}">
                            <a href="/presensi-kegiatan/{{ $k->slug }}" class="menu-link">
                                <div data-i18n="Presensi Pembelajaran">Presensi {{ $k->nama }}</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endcan
        @can('admin')
            <li class="menu-item {{ $title === 'Validasi Pembelajaran' ? 'active' : '' }}">
                <a href="/validasi-pembelajaran" class="menu-link">
                    {{-- <i class="menu-icon tf-icons bx bx-collection"></i> --}}
                    <i class='menu-icon tf-icons bx bx-check-square'></i>
                    <div data-i18n="Validasi Pembelajaran">Validasi Pembelajaran</div>
                </a>
            </li>
            <!-- Components -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Rekapitulasi</span></li>
            <!-- Data Rekapitulasi -->
            <li class="menu-item {{ $title === 'Daftar Pertemuan' ? 'active' : '' }}">
                <a href="/daftar-pertemuan" class="menu-link">
                    {{-- <i class="menu-icon tf-icons bx bx-collection"></i> --}}
                    <i class='menu-icon tf-icons bx bx-list-ul'></i>
                    <div data-i18n="Validasi Pembelajaran">Daftar Pertemuan</div>
                </a>
            </li>
            <li
                class="menu-item {{ ($title === 'Rekapitulasi Siswa' or $title === 'Rekapitulasi Guru') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-box"></i>
                    <div data-i18n="Pembelajaran">Pembelajaran</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ $title === 'Rekapitulasi Siswa' ? 'active' : '' }}">
                        <a href="/rekapitulasi-siswa" class="menu-link">
                            <div data-i18n="Rekapitulasi Siswa">Rekapitulasi Siswa</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Rekapitulasi Guru' ? 'active' : '' }}">
                        <a href="/rekapitulasi-guru" class="menu-link">
                            <div data-i18n="Rekapitulasi Guru">Rekapitulasi Guru</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('pimpinan')
            <li
                class="menu-item {{ ($title === 'Data Guru' or $title === 'Tahun Akademik' or $title === 'Data Kelas' or $title === 'Data Siswa' or $title === 'Mata Pelajaran' or $title === 'Data Narasumber' or $title === 'Data Angkatan' or $title === 'Data Wali Asrama' or $title === 'Data Kegiatan') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    {{-- <i class="menu-icon tf-icons bx bx-layout"></i> --}}
                    <i class='menu-icon tf-icons bx bx-data'></i>
                    <div>Data Master</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ $title === 'Data Guru' ? 'active' : '' }}">
                        <a href="/data-guru" class="menu-link">
                            <div data-i18n="Data Guru">Data Guru</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Data Wali Asrama' ? 'active' : '' }}">
                        <a href="/data-wali-asrama" class="menu-link">
                            <div data-i18n="Mata Pelajaran">Data Wali Asrama</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Tahun Akademik' ? 'active' : '' }}">
                        <a href="tahun-akademik" class="menu-link">
                            <div data-i18n="Tahun Akademik">Tahun Akademik</div>
                        </a>
                    </li>

                    <li class="menu-item {{ $title === 'Data Angkatan' ? 'active' : '' }}">
                        <a href="/data-angkatan" class="menu-link">
                            <div data-i18n="Mata Pelajaran">Data Angkatan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Data Kelas' ? 'active' : '' }}">
                        <a href="/kelas" class="menu-link">
                            <div data-i18n="Data Kelas">Data Kelas</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Data Siswa' ? 'active' : '' }}">
                        <a href="/siswa" class="menu-link">
                            <div data-i18n="Data Siswa">Data Siswa</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Mata Pelajaran' ? 'active' : '' }}">
                        <a href="/mata-pelajaran" class="menu-link">
                            <div data-i18n="Mata Pelajaran">Mata Pelajaran</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Data Kegiatan' ? 'active' : '' }}">
                        <a href="/data-kegiatan" class="menu-link">
                            <div data-i18n="Mata Pelajaran">Data Kegiatan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Data Narasumber' ? 'active' : '' }}">
                        <a href="/data-narasumber" class="menu-link">
                            <div data-i18n="Mata Pelajaran">Data Narasumber</div>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Operasional</span>
            </li>
            <li class="menu-item {{ $title === 'Rombongan Belajar' ? 'active' : '' }}">
                <a href="/rombongan-belajar" class="menu-link">
                    {{-- <i class='menu-icon bx bxs-group'></i> --}}
                    <i class='menu-icon bx bx-group'></i>
                    <div data-i18n="Rombongan Belajar">Rombongan Belajar</div>
                </a>
            </li>
            <li
                class="menu-item {{ ($title === 'Jadwal Pelajaran' or $title === 'Jadwal Guru Piket' or $title === 'Jadwal Pengganti' or $title === 'Jadwal Kegiatan') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-dock-top"></i>
                    <div data-i18n="Account Settings">Penjadwalan</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ $title === 'Jadwal Pelajaran' ? 'active' : '' }}">
                        <a href="/jadwal-pelajaran" class="menu-link">
                            <div data-i18n="Jadwal Pelajaran">Jadwal Pelajaran</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Jadwal Pengganti' ? 'active' : '' }}">
                        <a href="/jadwal-pengganti" class="menu-link">
                            <div data-i18n="Jadwal Pengganti">Jadwal Pengganti</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Jadwal Guru Piket' ? 'active' : '' }}">
                        <a href="/jadwal-guru-piket" class="menu-link">
                            <div data-i18n="Jadwal Guru Piket">Jadwal Guru Piket</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Jadwal Kegiatan' ? 'active' : '' }}">
                        <a href="/jadwal-kegiatan" class="menu-link">
                            <div data-i18n="Jadwal Kegiatan">Jadwal Kegiatan</div>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Components -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Rekapitulasi</span></li>
            <!-- Data Rekapitulasi -->
            <li class="menu-item {{ $title === 'Daftar Pertemuan' ? 'active' : '' }}">
                <a href="/daftar-pertemuan" class="menu-link">
                    {{-- <i class="menu-icon tf-icons bx bx-collection"></i> --}}
                    <i class='menu-icon tf-icons bx bx-list-ul'></i>
                    <div data-i18n="Validasi Pembelajaran">Daftar Pertemuan</div>
                </a>
            </li>
            <li
                class="menu-item {{ ($title === 'Rekapitulasi Siswa' or $title === 'Rekapitulasi Guru') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-box"></i>
                    <div data-i18n="Pembelajaran">Pembelajaran</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ $title === 'Rekapitulasi Siswa' ? 'active' : '' }}">
                        <a href="/rekapitulasi-siswa" class="menu-link">
                            <div data-i18n="Rekapitulasi Siswa">Rekapitulasi Siswa</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title === 'Rekapitulasi Guru' ? 'active' : '' }}">
                        <a href="/rekapitulasi-guru" class="menu-link">
                            <div data-i18n="Rekapitulasi Guru">Rekapitulasi Guru</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('guru')
            <li class="menu-item {{ $title === 'Jadwal Mengajar' ? 'active' : '' }}">
                <a href="/jadwal-mengajar" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-collection"></i>
                    <div data-i18n="Jadwal Mengajar">Jadwal Mengajar</div>
                </a>
            </li>
            <li class="menu-item {{ $title === 'Presensi Pembelajaran' ? 'active' : '' }}">
                <a href="/presensi-pembelajaran" class="menu-link">
                    {{-- <i class="menu-icon tf-icons bx bx-collection"></i> --}}
                    <i class='menu-icon tf-icons bx bx-log-in-circle'></i>
                    <div data-i18n="Presensi Pembelajaran">Presensi Pembelajaran</div>
                </a>
            </li>
            <li class="menu-item {{ $title === 'Validasi Pembelajaran' ? 'active' : '' }}">
                <a href="/validasi-pembelajaran" class="menu-link">
                    {{-- <i class="menu-icon tf-icons bx bx-collection"></i> --}}
                    <i class='menu-icon tf-icons bx bx-check-square'></i>
                    <div data-i18n="Validasi Pembelajaran">Validasi Pembelajaran</div>
                </a>
            </li>
            <!-- Components -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Rekapitulasi</span></li>

            <li class="menu-item {{ $title === 'Daftar Pertemuan' ? 'active' : '' }}">
                <a href="/daftar-pertemuan" class="menu-link">
                    {{-- <i class="menu-icon tf-icons bx bx-collection"></i> --}}
                    <i class='menu-icon tf-icons bx bx-list-ul'></i>
                    <div data-i18n="Validasi Pembelajaran">Daftar Pertemuan</div>
                </a>
            </li>
            <li class="menu-item {{ $title === 'Rekapitulasi Guru' ? 'active' : '' }}">
                <a href="/rekapitulasi-guru" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-collection"></i>
                    <div data-i18n="Rekap Pembelajaran">Rekap Pembelajaran</div>
                </a>
            </li>
        @endcan

        @can('siswa')
            <li class="menu-item {{ $title === 'Jadwal Siswa' ? 'active' : '' }}">
                <a href="/jadwal-siswa" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-collection"></i>
                    <div data-i18n="Jadwal Siswa">Jadwal Siswa</div>
                </a>
            </li>
            <!-- Components -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Rekapitulasi</span></li>
            <li class="menu-item {{ $title === 'Rekap Pembelajaran Siswa' ? 'active' : '' }}">
                <a href="/rekap-pembelajaran-siswa" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-collection"></i>
                    <div data-i18n="Pembelajaran">Pembelajaran</div>
                </a>
            </li>
        @endcan

    </ul>
</aside>
<!-- / Menu -->
