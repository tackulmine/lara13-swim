<script src="/assets/plugins/bs-custom-file-input/1.3.4/bs-custom-file-input.min.js"></script>
<script>
  $(document).ready(function() {
    // console.log(parseInt(moment().format('YYYY')), parseInt(moment().subtract(30, 'years').format('YYYY')));
    $('.dateOfBirth').daterangepicker({
      autoUpdateInput: false,
      singleDatePicker: true,
      showDropdowns: true,
      minYear: parseInt(moment().subtract(50, 'years').format('YYYY')),
      maxYear: parseInt(moment().subtract(2, 'years').format('YYYY')),
      locale: {
        format: "DD/MMM/YYYY",
        cancelLabel: 'Clear'
      }
    }).on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MMM/YYYY'));
    }).on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });

    bsCustomFileInput.init();

    $(".show-hide-btn").on('click', function(event) {
      event.preventDefault();
      var parent = $(this).closest('.show-hide-password');
      var input = parent.find('input');
      var icon = parent.find('i');
      var iconShowClass = icon.attr('data-toggle-showclass');
      var iconHideClass = icon.attr('data-toggle-hideclass');

      if (input.attr("type") == "text") {
        input.attr('type', 'password');
        icon.addClass(iconHideClass);
        icon.removeClass(iconShowClass);
      } else if (input.attr("type") == "password") {
        input.attr('type', 'text');
        icon.addClass(iconShowClass);
        icon.removeClass(iconHideClass);
      }
    });
  });
</script>
