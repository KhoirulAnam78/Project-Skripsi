@extends('layout.main2')

@section('content')
    <div class="container mb-3 mt-3">

        {{-- <img src="{{ url('') }}/assets/assets/img/titers.webp" class="img-fluid" alt="image.jpg"> --}}
        <div class="card" style="border-radius : 0px; padding:0px;margin:0px">
            <div class="card-body">
                <h5 class="card-title fw-bold">Selamat Datang</h5>
                <p class="card-text">Sistem informasi monitoring pembelajaran SMAN Titian Teras merupakan sistem yang
                    digunakan untuk memonitoring pembelajaran yang terjadi di sekolah. </p>
            </div>
            <img src="{{ url('') }}/assets/assets/img/titers.webp" class="card-img-top" style="border-radius : 0px"
                alt="image.jpg">
            <div class="card-body">
                <p class="card-text">Pengguna sistem ini adalah seluruh guru di SMAN Titian Teras dan seluruh Wali Murid.
                    Login terlebih dahulu untuk menggunakan sistem</p>
                <a href="/login" class="btn btn-primary">Login</a>
            </div>
        </div>
    </div>
@endsection
