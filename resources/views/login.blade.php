<!DOCTYPE html>

<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ url('') }}/assets/assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login | SMAN TITIAN TERAS</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ url('') }}/assets/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ url('') }}/assets/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ url('') }}/assets/assets/vendor/css/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ url('') }}/assets/assets/vendor/css/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ url('') }}/assets/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="{{ url('') }}/assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ url('') }}/assets/assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="{{ url('') }}/assets/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ url('') }}/assets/assets/js/config.js"></script>
    @livewireStyles
</head>

<body class="bg-primary">
    <!-- Content -->

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
                        <h4 class="my-2" align='center'>SiMonEv Pembelajaran</h4>
                        <h5 align='center'>SMAN TITIAN TERAS</h5>
                        <p class="mb-2">Silahkan login terlebih dahulu</p>
                        @if (session()->has('loginError'))
                            <div class="mb-2">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('loginError') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                        @endif
                        <livewire:login-form />
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:{{ url('') }}/assets assets/vendor/js/core.js -->
    <script src="{{ url('') }}/assets/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ url('') }}/assets/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ url('') }}/assets/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ url('') }}/assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="{{ url('') }}/assets/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ url('') }}/assets/assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    @livewireScripts
</body>

</html>
