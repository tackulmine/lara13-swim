<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  @stack('meta')

  <title>{{ $pageTitle }} | {{ config('app.name') }}</title>

  <!-- Custom fonts for this template-->
  <link href="/assets/back/vendor/Font-Awesome/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="//fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="/assets/back/css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link rel="stylesheet" href="/assets/back/vendor/datatables/dataTables.bootstrap4.min.css">
  {{-- <link rel="stylesheet" href="/assets/back/vendor/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/back/vendor/datatables.net-fixedheader/fixedHeader.bootstrap4.min.css"> --}}

  {{-- <link rel="stylesheet" href="/assets/back/vendor/daterangepicker/daterangepicker.min.css" /> --}}

  <link rel="stylesheet" href="/assets/back/vendor/select2/dist/css/select2.min.css">
  {{-- //github.com/ttskch/select2-bootstrap4-theme --}}
  <link rel="stylesheet" href="/assets/back/vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  {{-- <link rel="stylesheet" href="/assets/back/vendor/bootstrap-select/dist/css/bootstrap-select.min.css"> --}}

  @stack('css')

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    {{-- @include('layouts._sidebar') --}}

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        {{-- @include('layouts._topbar') --}}

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
            @stack('copyright-before')
            <span>
              @if (!empty($event->is_has_copyright) && !empty($event->copyright_text))
                &copy; {{ $event->copyright_text }}
              @elseif (empty($event->is_has_copyright))
                &copy; {{ config('app.name') }} {{ now()->format('Y') }}
              @endif
            </span>
            @stack('copyright-after')
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

  <!-- Bootstrap core JavaScript-->
  <script src="/assets/back/vendor/jquery/jquery.min.js"></script>
  <script src="/assets/back/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="/assets/back/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="/assets/back/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="/assets/back/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="/assets/back/vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="/assets/back/vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
  <script src="/assets/back/vendor/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

  {{-- <script src="/assets/back/vendor/mark.js/dist/jquery.mark.min.js"></script>
    <script src="/assets/back/vendor/datatables.mark.js/dist/datatables.mark.min.js"></script>
    <script src="/assets/back/vendor/datatables.net-fixedheader/dataTables.fixedHeader.min.js"></script> --}}

  <script src="/assets/back/vendor/select2/dist/js/select2.min.js"></script>
  <script src="/assets/back/vendor/select2/dist/js/i18n/id.js"></script>

  <script src="/assets/back/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
  <script src="/assets/back/vendor/bootstrap-select/dist/js/i18n/defaults-id_ID.min.js"></script>

  {{-- <script src="/assets/back/vendor/daterangepicker/moment.min.js"></script>
    <script src="/assets/back/vendor/daterangepicker/daterangepicker.min.js"></script> --}}

  <script src="/assets/plugins/inputmask/5.0.8/jquery.inputmask.min.js"></script>
  <script src="/assets/plugins/fancybox/3.5.7/jquery.fancybox.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="/assets/back/js/demo/datatables-demo.js?{!! filemtime(public_path('assets/back/js/demo/datatables-demo.js')) !!}"></script>

  <script>
    var initMask = function() {
      // $('.input-mask-time').mask('00:00:00');
      $(":input").inputmask();
    };

    var initDtSelect2 = function() {
      // select2
      // $.fn.select2.defaults.set("theme", "bootstrap");
      $('select:not([name=dataTable_length]):not(.selectpicker)').each(function() {
        $(this).select2({
          theme: 'bootstrap4',
          width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
          // tags: $(this).data('tags') ? true : false,
          placeholder: $(this).data('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
          closeOnSelect: !$(this).attr('multiple'),
        });
      });
    };

    var initToggleDisabled = function() {
      $(document).on("change", "[data-toggle-disabled-id]", function() {
        var $target = $(this);
        var $id = $target.data('toggle-disabled-id');
        var $toggle = $('#' + $id);
        $toggle.prop('disabled', !$target.prop('checked'));
      });
    };

    var initAsterikForm = function() {
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
    };

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
    // var forceKeyPressUppercase = function(e) {
    //     var charInput = e.keyCode;
    //     if ((charInput >= 97) && (charInput <= 122)) { // lowercase
    //         if (!e.ctrlKey && !e.metaKey && !e.altKey) { // no modifier key
    //             var newChar = charInput - 32;
    //             var start = e.target.selectionStart;
    //             var end = e.target.selectionEnd;
    //             e.target.value = e.target.value.substring(0, start) + String.fromCharCode(newChar) + e.target.value
    //                 .substring(end);
    //             e.target.setSelectionRange(start + 1, start + 1);
    //             e.preventDefault();
    //         }
    //     }
    // }
    // $(document).on("keypress", '.toUppercase', function(e) {
    //     forceKeyPressUppercase(e);
    //     return;
    // });

    $(document).on("ajaxComplete", function() {
      initMask();
      initDtSelect2();
    });

    $(document).ready(function() {
      initMask();
      initDtSelect2();
      initToggleDisabled();
      initAsterikForm();
    });
  </script>

  @stack('js')

</body>

</html>
