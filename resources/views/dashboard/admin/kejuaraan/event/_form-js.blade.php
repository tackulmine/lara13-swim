{{-- <script src="/assets/plugins/bs-custom-file-input/1.3.4/bs-custom-file-input.min.js"></script> --}}
<script>
  var initDateRangePicker = function() {
    $('.daterange').daterangepicker({
      'showDropdowns': true,
      // "timePicker": true,
      // "timePicker24Hour": true,
      // "timePickerIncrement": 5,
      // "timePickerSeconds": true,
      "locale": {
        // "format": "DD/MM/YYYY HH:mm",
        "format": "DD/MM/YYYY",
      }
    });
  };
  $(document).ready(function() {
    // bsCustomFileInput.init();
    initDateRangePicker();
  });
</script>
