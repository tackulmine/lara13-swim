<script src="/assets/plugins/bs-custom-file-input/1.3.4/bs-custom-file-input.min.js"></script>
<script>
  var initDatePicker = function() {
    $('input.date').daterangepicker({
      autoUpdateInput: false,
      singleDatePicker: true,
      showDropdowns: true,
      minYear: parseInt(moment().format('YYYY')),
      maxYear: parseInt(moment().format('YYYY')),
      locale: {
        format: "DD/MM/YYYY",
        cancelLabel: 'Clear'
      }
    }).on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
    }).on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
  };
  var initDateRangePicker = function() {
    $('.daterange').daterangepicker({
      showDropdowns: true,
      // timePicker: true,
      // timePicker24Hour: true,
      // timePickerIncrement: 5,
      // timePickerSeconds: true,
      locale: {
        // format: "DD/MM/YYYY HH:mm",
        format: "DD/MM/YYYY",
      }
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

  $(document).ready(function() {
    bsCustomFileInput.init();
    initDatePicker();
    initDateRangePicker();
    initToggleDisabled();

    $('input[name="is_reg"]').on('change', function(el) {
      if ($(this).is(':checked')) {
        $('input[name="reg_end_date"], input[name="reg_quota"]').prop('disabled', false);
      } else {
        $('input[name="reg_end_date"], input[name="reg_quota"]').prop('disabled', true);
      }
    })
    $('input[name="is_has_copyright"]').on('change', function(el) {
      if ($(this).is(':checked')) {
        $('input[name="copyright_text"]').prop('disabled', false);
      } else {
        $('input[name="copyright_text"]').prop('disabled', true);
      }
    })
  });
</script>
