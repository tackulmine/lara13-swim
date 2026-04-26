<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>{{ $pageTitle ?? config('app.name') }}</title>

  <!-- Custom fonts for this template-->
  <link rel="stylesheet"
    href="//fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">

  <!-- Custom styles for this template-->
  <link rel="stylesheet" href="/assets/back/css/sb-admin-2.min.css">
  <style>
    body,
    html {
      color: black;
      font-size: 1.2rem;
    }

    .container-bg {
      position: relative;
    }

    @media print {
      .container-bg {
        padding: 1rem;
        /* border: 2px solid gray; */
        /* border-width: 3px;
        border-style: solid;
        border-color: #efefef;
        border-image: linear-gradient(
            to bottom,
            black,
            lightgray
          ) 1;
        */
        /* background:
          linear-gradient(white, white) padding-box,
          linear-gradient(to bottom, darkgray, lightgray) border-box;
        border: 3px solid transparent;
        border-radius: .2rem 2rem;
        height: 99vh; */
        /* padding-bottom: 10vh; */
      }
    }

    .container-bg::before {
      content: "";
      background: transparent url('/assets/front/images/logo/logo-centrum.jpg') no-repeat;
      -moz-filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");
      -o-filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");
      -webkit-filter: grayscale(100%);
      filter: gray;
      filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");
      opacity: 0.15;
      background-size: cover;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      /* z-index: -1; */
      width: 50vw;
      height: 50vw;
    }

    .table {
      /* width: auto; */
      background: transparent !important;
      color: black;
    }

    .table tr {
      background: transparent !important;
    }

    .table tr td,
    .table tr th {
      background: transparent !important;
    }
  </style>

  @stack('css')

</head>

<body>

  <div class="container-fluid container-bg">
    @yield('content')
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="/assets/back/vendor/jquery/jquery.min.js"></script>
  {{-- <script src="/assets/back/vendor/bootstrap/js/bootstrap.bundle.min.js"></script> --}}

  <!-- Core plugin JavaScript-->
  {{-- <script src="/assets/back/vendor/jquery-easing/jquery.easing.min.js"></script> --}}

  <!-- Custom scripts for all pages-->
  {{-- <script src="/assets/back/js/sb-admin-2.min.js"></script> --}}
  <script>
    $(window).on('load', function() {
      setTimeout(function() {
        window.print();
      }, 2000);
      window.onafterprint = function() {
        setTimeout(function() {
          window.close();
        }, 500);
      }
    });
  </script>

  @stack('js')

</body>

</html>
