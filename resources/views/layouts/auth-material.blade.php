<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('material/assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('material/assets/img/favicon.png') }}">
  <title>
    Marmoraria Orçamentos - Autenticação
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

<body class="bg-gray-200">
  <main class="main-content mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">@yield('auth_title', 'Autenticação')</h4>
                   <div class="row mt-3">
                    {{-- Optional Social Login Buttons --}}
                  </div>
                </div>
              </div>
              <div class="card-body">
                 {{-- Display Validation Errors --}}
                 @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible text-white fade show mb-4" role="alert">
                         <span class="alert-icon align-middle">
                           <span class="material-icons text-md">warning</span>
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
                {{-- Display Session Status --}}
                 @if (session('status'))
                    <div class="alert alert-success alert-dismissible text-white fade show mb-4" role="alert">
                        <span class="alert-icon align-middle">
                          <span class="material-icons text-md">thumb_up_off_alt</span>
                        </span>
                        <span class="alert-text">{{ session('status') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @yield('auth_content')

              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-12 col-md-6 my-auto">
              <div class="copyright text-center text-sm text-white text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>, feito com <i class="fa fa-heart" aria-hidden="true"></i> por
                <a href="https://www.creative-tim.com" class="font-weight-bold text-white" target="_blank">Creative Tim</a> & Adaptado por Manus AI.
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
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
