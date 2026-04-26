<script>
  // console.log(parseInt(moment().format('YYYY'),10), parseInt(moment().subtract(30, 'years').format('YYYY')));
  $('.dateOfBirth').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: false,
    minYear: parseInt(moment().subtract(30, 'years').format('YYYY')),
    maxYear: parseInt(moment().format('YYYY'), 10),
    "locale": {
      "format": "DD/MMM/YYYY",
    }
  });

  $('.dateOfBirth').on('apply.daterangepicker', function(ev, picker) {
    // $(this).val(picker.startDate.format('DD/MMM/YYYY') + ' - ' + picker.endDate.format('DD/MMM/YYYY'));
    $(this).val(picker.startDate.format('DD/MMM/YYYY'));
  });

  $('.dateOfBirth').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });
</script>
