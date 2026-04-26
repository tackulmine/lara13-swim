<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}

  <style type="text/css">
    {{ file_get_contents(public_path('assets/back/css/bootstrap-v4.0.0.min.css')) }} @font-face {
      font-family: 'Bahnschrift';
      src: url('{{ asset('assets') }}/fonts/Bahnschrift.eot');
      src: url('{{ asset('assets') }}/fonts/Bahnschrift.eot?#iefix') format('embedded-opentype'),
        url('{{ asset('assets') }}/fonts/Bahnschrift.woff2') format('woff2'),
        url('{{ asset('assets') }}/fonts/Bahnschrift.woff') format('woff'),
        url('{{ asset('assets') }}/fonts/Bahnschrift.ttf') format('truetype'),
        url('{{ asset('assets') }}/fonts/Bahnschrift.svg#Bahnschrift') format('svg');
      font-weight: normal;
      font-style: normal;
      font-display: swap;
    }

    body {
      font-family: Bahnschrift;
      font-size: .9rem;
    }
  </style>
  {{-- <script>
    var pdfInfo = {};
    var x = document.location.search.substring(1).split('&');
    for (var i in x) { var z = x[i].split('=',2); pdfInfo[z[0]] = unescape(z[1]); }
    function getPdfInfo() {
      var page = pdfInfo.page || 1;
      var pageCount = pdfInfo.topage || 1;
      document.getElementById('pdfkit_page_current').textContent = page;
      document.getElementById('pdfkit_page_count').textContent = pageCount;
    }
    </script> --}}
</head>

<body>
  <div class="text-center pb-2 pt-4">
    {{-- <h6>&copy; {{ config('app.name') }}</h6> --}}
    <table class="w-100" style="font-size: smaller;">
      <tr>
        <td class="text-left" width="33%">&nbsp;</td>
        <td class="text-center" width="34%">
          @if (!empty($event->is_has_copyright) && !empty($event->copyright_text))
            &copy; {{ $event->copyright_text }}
          @elseif (empty($event->is_has_copyright))
            &copy; {{ config('app.name') }}
          @endif
        </td>
        <td class="text-right" width="33%">
          {{-- Page <span id='page'></span> of
                    <span id='topage'></span>
                    <script>
                      var vars={};
                      var x=window.location.search.substring(1).split('&');
                      for (var i in x) {
                        var z=x[i].split('=',2);
                        vars[z[0]] = unescape(z[1]);
                      }
                      document.getElementById('page').innerHTML = vars.page;
                      document.getElementById('topage').innerHTML = vars.topage;
                    </script> --}}
        </td>
      </tr>
    </table>
  </div>
</body>

</html>
