<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid mx-3">
        <div class="app-brand demo m-0">
            <a href="/" class="app-brand-link">
                {{-- <div class="app-brand-logo demo">
                  <img src="{{ url('') }}/assets/assets/img/icons/brands/sman-titian-teras.jpg"
                      alt="Logo SMAN TITIAN TERAS.jpg" width="40px">
              </div> --}}
                <span class="app-brand-text demo menu-text fw-bolder"
                    style="background-color: #0402FC; font-weight : bold;
          background-image: linear-gradient(45deg,#0402FC, #FC0204,#FCFE04);
          background-size: 100%; background-repeat: repeat;-webkit-background-clip: text;
          -webkit-text-fill-color: transparent; 
          -moz-background-clip: text;
          -moz-text-fill-color: transparent;">Simobel</span>
            </a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse mt-2 justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item {{ $title === 'Beranda' ? 'active' : '' }} mx-2">
                    <a class="nav-link" aria-current="page" href="/">Beranda</a>
                </li>
                <li class="nav-item {{ $title === 'Monitoring Pembelajaran' ? 'active' : '' }} mx-2">
                    <a class="nav-link" href="/monitoring">Monitoring</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary nav-link text-white mx-2" style="background-color: #0402FC !important"
                        href="/login">Login</a>
                </li>
            </ul>

        </div>
    </div>
</nav>
