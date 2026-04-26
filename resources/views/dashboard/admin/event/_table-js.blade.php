<script>
  $(document).ready(function() {
    $(document).on('click', '.btn-edit', function() {
      // console.log('edit');
      var href = $(this).attr('href');
      var title = $(this).attr('data-title');
      var target = $(this).attr('data-target');
      var action = $(this).attr('data-action');

      $(target).find('.modal-title').html(title);
      $(target).find('form').first().attr('action', action);

      $.ajax({
        type: "get",
        url: href,
        success: function(response) {
          $(target).find('.modal-body').html(response);
          initDateRangePicker();
        }
      });

    });
    $(document).on('submit', '.form-disabled-submit', function() {
      $(this).find("button[type=submit]").each(function(el) {
        $(this).prop('disabled', true);
      });
    });
    $('#myEditModal').on('hidden.bs.modal', function(e) {
      var form = $(this).find('form').first();
      $(this).find('form').first().attr('action', '#!');
      $(this).find('.modal-body').html('');
    });
  });
</script>

<script>
  const indexes = [];
</script>
@if (!auth()->user()->hasRole('external') || auth()->user()->isSuperuser())
  <script>
    indexes.push(7, 11);
  </script>
@else
  <script>
    indexes.push(10);
  </script>
@endif
<script>
  var t = $('#dataTableFooterCustom').DataTable({
    columnDefs: [{
      targets: 0,
      searchable: false,
      orderable: false,
      className: 'select-checkbox',
      'render': function(data, type, full, meta) {
        return '<input type="checkbox" name="ids[]" value="' + $('<div/>').text(
          data).html() + '">';
      }
    }],
  });

  t.on('order.dt search.dt', function() {
    t.column(1, {
      search: 'applied',
      order: 'applied'
    }).nodes().each(function(cell, i) {
      cell.innerHTML = i + 1;
    });
  }).draw();

  // Handle click on "Select all" control
  $('#select-all').on('click', function() {
    // Get all rows with search applied
    var rows = t.rows({
      'search': 'applied'
    }).nodes();
    // Check/uncheck checkboxes for all rows in the table
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
  });

  // Handle click on checkbox to set state of "Select all" control
  $('#dataTableFooterCustom tbody').on('change', 'input[type="checkbox"]', function() {
    // If checkbox is not checked
    if (!this.checked) {
      var el = $('#select-all').get(0);
      // If "Select all" control is checked and has 'indeterminate' property
      if (el && el.checked && ('indeterminate' in el)) {
        // Set visual state of "Select all" control
        // as 'indeterminate'
        el.indeterminate = true;
      }
    }
  });

  // Handle form submission event
  $('#kompetisi-form').on('submit', function(e) {
    var form = this;
    var text = $(this).find('[type="submit"]').attr('data-original-title');

    if (confirm('Yakin ' + text + '?')) {
      // Iterate over all checkboxes in the table
      t.$('input[type="checkbox"]').each(function() {
        // If checkbox doesn't exist in DOM
        if (!$.contains(document, this)) {
          // If checkbox is checked
          if (this.checked) {
            // Create a hidden element
            $(form).append(
              $('<input>')
              .attr('type', 'hidden')
              .attr('name', this.name)
              .val(this.value)
            );
          }
        }
      });

      return true;
    }

    return false;
  });
</script>
