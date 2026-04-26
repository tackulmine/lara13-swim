<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>{{ $pageTitle ?? config('app.name') }} - Login</title>

  <!-- Custom fonts for this template-->
  <link href="/assets/back/vendor/Font-Awesome/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="//fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="/assets/back/css/sb-admin-2.min.css" rel="stylesheet">

  <style>
    form.user .custom-radio.small label {
      line-height: 1.5rem;
    }

    form.user .custom-file-input,
    form.user .custom-file-label,
    form.user .custom-file-label::after {
      height: auto;
      padding: .75rem 1rem;
    }

    form.user .custom-file-input,
    form.user .custom-file-label {
      border-radius: 10rem;
    }

    form.user .custom-file {
      height: auto;
    }

    form.user .custom-select {
      border-radius: 10rem;
      padding: .75rem 1rem;
      height: auto;
    }

    form.user .input-group-text {
      border-radius: 10rem;
    }
  </style>

  @stack('css')
</head>

<body class="bg-gradient-primary">

  <div class="container">

    @yield('content')

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="/assets/back/vendor/jquery/jquery.min.js"></script>
  <script src="/assets/back/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="/assets/back/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="/assets/back/js/sb-admin-2.min.js"></script>

  <script>
    // to UPPERCASE
    $(document).on("input", '.toUppercase', function(e) {
      let p = this.selectionStart;
      this.value = this.value.toUpperCase();
      this.setSelectionRange(p, p);
    });
    $(document).on("input", '.toLowercase', function(e) {
      let p = this.selectionStart;
      this.value = this.value.toLowerCase();
      this.setSelectionRange(p, p);
    });
  </script>

  @stack('js')

</body>

</html>
