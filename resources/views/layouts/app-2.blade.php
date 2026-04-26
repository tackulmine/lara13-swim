<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>{{ $pageTitle ?? config('app.name') }} | Admin</title>

  <!-- Custom fonts for this template-->
  <link rel="stylesheet" href="/assets/back/vendor/Font-Awesome/css/all.min.css">
  <link rel="stylesheet"
    href="//fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">

  <!-- Custom styles for this template-->
  <link rel="stylesheet" href="/assets/back/css/sb-admin-2.min.css">

  <!-- Custom styles for this page -->
  <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="//cdn.datatables.net/responsive/3.0.5/css/responsive.bootstrap4.css">
  <link rel="stylesheet" href="//cdn.datatables.net/fixedheader/4.0.3/css/fixedHeader.bootstrap4.css">

  {{-- <link rel="stylesheet" href="/assets/back/vendor/datatables/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/assets/back/vendor/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="/assets/back/vendor/datatables.net-fixedheader/fixedHeader.bootstrap4.min.css"> --}}

  <link rel="stylesheet" href="/assets/back/vendor/daterangepicker/daterangepicker.min.css" />

  <link rel="stylesheet" href="/assets/back/vendor/select2/dist/css/select2.min.css">
  {{-- //github.com/ttskch/select2-bootstrap4-theme --}}
  <link rel="stylesheet" href="/assets/back/vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  <link rel="stylesheet" href="/assets/back/vendor/bootstrap-select/dist/css/bootstrap-select.min.css">

  <style>
    @media (min-width: 576px) {
      .w-sm-auto {
        width: auto !important;
      }
    }
  </style>

  @stack('css')

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    @include('layouts._sidebar')

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        @include('layouts._topbar')

        <!-- Begin Page Content -->
        <div class="container-fluid">

          @yield('content')

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; {{ config('app.name') }} {{ now()->format('Y') }}</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __('Ready to Leave?') }}</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">{{ __('Select "Logout" below if you are ready to end your current session.') }}
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>
          {{-- <a class="btn btn-primary" href="login.html">Logout</a>
                    --}}
          <a class="btn btn-primary" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="/assets/back/vendor/jquery/jquery.min.js"></script>
  <script src="/assets/back/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="/assets/back/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="/assets/back/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="//cdn.datatables.net/2.3.2/js/dataTables.js"></script>
  <script src="//cdn.datatables.net/2.3.2/js/dataTables.bootstrap4.js"></script>
  <script src="//cdn.datatables.net/responsive/3.0.5/js/dataTables.responsive.js"></script>
  <script src="//cdn.datatables.net/responsive/3.0.5/js/responsive.bootstrap4.js"></script>
  <script src="//cdn.datatables.net/fixedheader/4.0.3/js/dataTables.fixedHeader.js"></script>
  <script src="//cdn.datatables.net/fixedheader/4.0.3/js/fixedHeader.bootstrap4.js"></script>

  {{-- <script src="/assets/back/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="/assets/back/vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="/assets/back/vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
  <script src="/assets/back/vendor/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script> --}}

  {{-- <script src="/assets/back/vendor/mark.js/dist/jquery.mark.min.js"></script>
  <script src="/assets/back/vendor/datatables.mark.js/dist/datatables.mark.min.js"></script>
  <script src="/assets/back/vendor/datatables.net-fixedheader/dataTables.fixedHeader.min.js"></script> --}}

  <script src="/assets/back/vendor/select2/dist/js/select2.min.js"></script>
  <script src="/assets/back/vendor/select2/dist/js/i18n/id.js"></script>

  <script src="/assets/back/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
  <script src="/assets/back/vendor/bootstrap-select/dist/js/i18n/defaults-id_ID.min.js"></script>

  <script src="/assets/back/vendor/daterangepicker/moment.min.js"></script>
  <script src="/assets/back/vendor/daterangepicker/daterangepicker.min.js"></script>

  {{-- <script src="/assets/plugins/bootstrap-validator/0.11.9/validator.min.js"></script> --}}

  <!-- Page level custom scripts -->
  <script src="/assets/back/js/demo/datatables-demo.js?{!! filemtime(public_path('assets/back/js/demo/datatables-demo.js')) !!}"></script>

  <script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
  </script>

  {{-- <script>
        $('form').each(function() {
            var form = $(this);
            var submitBtn = form.find('[type=submit]');
            var disabledSubmit = false;
            form.find('[required]').each(function() {
                var thisVal = $(this).val().trim();
                if (thisVal === "") {
                    disabledSubmit = true;
                    return false; // break
                }
            });
            if (disabledSubmit === true) {
                submitBtn.prop('disabled', true);
            } else {
                submitBtn.prop('disabled', false);
            }
        });

        $(document).on('change keyup blur', '[required]', function() {
            var thisVal = $(this).val().trim();
            var form = $(this).closest('form');
            var submitBtn = form.find('[type=submit]');
            if (thisVal === '') {
                submitBtn.prop('disabled', true);
            }
            // re-check all required fields
            var disabledSubmit = false;
            form.find('[required]').each(function() {
                var thisVal = $(this).val().trim();
                if (thisVal === '') {
                    disabledSubmit = true;
                    return false; // break
                }
            });
            if (disabledSubmit === true) {
                submitBtn.prop('disabled', true);
            } else {
                submitBtn.prop('disabled', false);
            }
        });

        $(document).on("click", "[type=submit]:not([disabled])", function(e) {
            // e.preventDefault();
            var text = $(this).html();
            var btn = true;

            if (text == null) {
                text = $(this).val();
                btn = false;
            }

            if (btn) {
                $(this).prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-pulse"></i> Loading...');
                var name = $(this).attr('name');
                var val = $(this).val();
                if (name !== undefined && val !== undefined) {
                    $("<input type='hidden' name='" + name + "' value='" + val + "'>").insertAfter(
                        $(this));
                }
            } else {
                $(this).prop('disabled', true).val('Loading...');
            }

            $(this).closest('form').submit();
        });
    </script> --}}

  <script>
    var unique = function(array) {
      return $.grep(array, function(el, index) {
        return index === $.inArray(el, array);
      });
    }

    var removeHTMLTags = function(htmlString) {
      // Create a new DOMParser instance
      const parser = new DOMParser();
      // Parse the HTML string into a DOM document
      const doc = parser.parseFromString(htmlString, 'text/html');
      // Extract the text content from the parsed document
      const textContent = doc.body.textContent || "";
      return textContent.trim(); // Trim any leading or trailing whitespace
    };

    // Remove the formatting to get integer data for summation
    var intVal = function(i) {
      return typeof i === 'string' ?
        i.replace(/[\$.]/g, '').replace(/<[^>]*>?/gm, '') * 1 :
        typeof i === 'number' ?
        parseInt(i) : 0;
    };

    var formatNumber = function(n) {
      return n.toLocaleString('id-ID'); // or whatever you prefer here
    };

    var checkUniqueValues = function(values) {
      var ret = [];
      values.each(function(value, index) {
        value = removeHTMLTags(value);

        if (jQuery.inArray(value, ret) === -1) {
          ret.push(value);
        }
      });

      return ret;
    };

    if ($(window).width() <= 768) {
      $('#page-top').addClass('sidebar-toggled');
      $('#accordionSidebar').addClass('toggled');
    }

    $(document).ready(function() {
      // fix tooltip on datatable
      $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
      });

      // add asterik to all label
      $('[required]').each(function() {
        var parent = $(this).closest('.form-group');
        var label = parent.find('label').first();
        if (label.length) {
          label.find('.text-danger').remove();
          label.find('*').remove();
          label.append('<span class="text-danger">*</span>')
        }
      });

      // select2
      // $.fn.select2.defaults.set("theme", "bootstrap");
      $('select:not([name=dataTable_length]):not([name=dataTableFooterCustom_length]):not(.selectpicker)').each(
        function() {
          $(this).select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass(
              'w-100') ? '100%' : 'style',
            // tags: $(this).data('tags') ? true : false,
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: !$(this).attr('multiple'),
          });
        });
      // FIXED Search not auto focusing in jQuery 3.6.0
      $(document).on('select2:open', function() {
        // let allFound = document.querySelectorAll('.select2-container--open .select2-search__field');
        // allFound[allFound.length - 1].focus();
        window.setTimeout(function() {
          $(".select2-container--open .select2-search__field").get(0).focus();
        }, 200);
      });

      // to UPPERCASE
      $(document).on("input", '.toUppercase', function(e) {
        let p = this.selectionStart;
        this.value = this.value.toUpperCase();
        this.setSelectionRange(p, p);
      });
    });
  </script>

  @stack('js')

</body>

</html>
