<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('material/assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('material/assets/img/favicon.png') }}">
  <title>
    Marmoraria Orçamentos - @yield('title', 'Dashboard')
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('material/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('material/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('material/assets/css/material-dashboard.css?v=3.0.0') }}" rel="stylesheet" />
  @stack('styles')
</head>

<body class="g-sidenav-show  bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="{{ route('orcamentos.index') }}" >
        <img src="{{ asset('material/assets/img/logo-ct.png') }}" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">Marmoraria Orçamentos</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white {{ request()->routeIs('orcamentos.*') ? 'active bg-gradient-primary' : '' }}" href="{{ route('orcamentos.index') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">Orçamentos</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white {{ request()->routeIs('clientes.*') ? 'active bg-gradient-primary' : '' }}" href="{{ route('clientes.index') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">person</i>
            </div>
            <span class="nav-link-text ms-1">Clientes</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white {{ request()->routeIs('materiais.*') ? 'active bg-gradient-primary' : '' }}" href="{{ route('materiais.index') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">inventory_2</i>
            </div>
            <span class="nav-link-text ms-1">Materiais</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white {{ request()->routeIs('servicos.*') ? 'active bg-gradient-primary' : '' }}" href="{{ route('servicos.index') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">build</i>
            </div>
            <span class="nav-link-text ms-1">Serviços</span>
          </a>
        </li>
        {{-- Link para Relatórios --}}
        <li class="nav-item">
          <a class="nav-link text-white {{ request()->routeIs('orcamentos.relatorio.*') ? 'active bg-gradient-primary' : '' }}" href="{{ route('orcamentos.relatorio.form') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">assessment</i>
            </div>
            <span class="nav-link-text ms-1">Relatórios</span>
          </a>
        </li>
         <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Conta</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white {{ request()->routeIs('profile.edit') ? 'active bg-gradient-primary' : '' }}" href="{{ route('profile.edit') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">person</i>
            </div>
            <span class="nav-link-text ms-1">Perfil</span>
          </a>
        </li>
        {{-- Logout Link/Form --}}
        <li class="nav-item">
           <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="nav-link text-white " href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                   <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">logout</i>
                    </div>
                    <span class="nav-link-text ms-1">Sair</span>
                </a>
            </form>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Páginas</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">@yield('title', 'Dashboard')</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">@yield('title', 'Dashboard')</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            {{-- Search Input --}}
            {{-- <div class="input-group input-group-outline">
              <label class="form-label">Type here...</label>
              <input type="text" class="form-control">
            </div> --}}
          </div>
          <ul class="navbar-nav  justify-content-end">
             <li class="nav-item d-flex align-items-center">
                 <span class="nav-link text-body font-weight-bold px-0">
                    <i class="fa fa-user me-sm-1"></i>
                    <span class="d-sm-inline d-none">{{ Auth::user()->name ?? 'Usuário' }}</span>
                 </span>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
            {{-- Settings Icon --}}
            {{-- <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0">
                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
              </a>
            </li> --}}
            {{-- Notifications Dropdown --}}
            {{-- <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>
              <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                </ul>
            </li> --}}
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      @if (session('success'))
          <div class="alert alert-success alert-dismissible text-white fade show" role="alert">
              <span class="alert-icon align-middle">
                <span class="material-icons text-md">thumb_up_off_alt</span>
              </span>
              <span class="alert-text"><strong>Sucesso!</strong> {{ session('success') }}</span>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif
      @if (session('error'))
          <div class="alert alert-danger alert-dismissible text-white fade show" role="alert">
              <span class="alert-icon align-middle">
                <span class="material-icons text-md">warning</span>
              </span>
              <span class="alert-text"><strong>Erro!</strong> {{ session('error') }}</span>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif
      @if ($errors->any())
          <div class="alert alert-danger alert-dismissible text-white fade show" role="alert">
               <span class="alert-icon align-middle">
                 <span class="material-icons text-md">error</span>
               </span>
               <span class="alert-text"><strong>Atenção!</strong> Verifique os erros abaixo:</span>
               <ul class="mb-0">
                   @foreach ($errors->all() as $error)
                       <li>{{ $error }}</li>
                   @endforeach
               </ul>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
      @endif

      @yield('content')

      <footer class="footer py-4  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>, feito com <i class="fa fa-heart"></i> por
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a> & Adaptado por Manus AI.
              </div>
            </div>
            {{-- Footer Links --}}
            {{-- <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
              </ul>
            </div> --}}
          </div>
        </div>
      </footer>
    </div>
  </main>

  {{-- Settings Plugin --}}
  {{-- <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="material-icons py-2">settings</i>
    </a>
    <div class="card shadow-lg">
      </div>
  </div> --}}

  <!--   Core JS Files   -->
  <script src="{{ asset('material/assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('material/assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('material/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('material/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('material/assets/js/material-dashboard.min.js?v=3.0.0') }}"></script>
  @stack('scripts')
</body>

</html>

