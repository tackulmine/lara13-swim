<script>
  $(document).ready(function() {
    var t = $('#dataTableCustom').DataTable({
      stateSave: true,
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
      initComplete: function() {
        this.api().columns([3, 4]).every(function() {
          var column = this;
          var select = $(
              '<select class="select2me" style="padding-right:20px;"><option value="">---</option></select>'
            )
            .appendTo($(column.footer()).empty())
            .on('change', function() {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );

              column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });

          column.data().unique().sort().each(function(d, j) {
            select.append('<option value="' + d + '">' + d +
              '</option>')
          });
        });
        initSelectToMe();
      }
    });

    t.on('order.dt search.dt', function() {
      t.column(1, {
        search: 'applied',
        order: 'applied'
      }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();

    $('.btn-dt-state-clear').on('click', function() {
      t.state.clear();
      window.location.reload();
    });

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
    $('#dataTableCustom tbody').on('change', 'input[type="checkbox"]', function() {
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
    $('#school-form').on('submit', function(e) {
      var form = this;

      if (confirm(
          'Yakin menghapus semua data? \n\nHATI-HATI!! Data yang terhapus tidak akan bisa dikembalikan!'
        )) {
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

    // Handle merge school btn click
    $(document).on('click', '.btn-merger', function(e) {
      e.preventDefault();
      var $form = $('#school-form');
      // console.log('edit');
      var href = $(this).attr('href');
      var title = $(this).attr('data-title');
      var target = $(this).attr('data-target');
      var action = $(this).attr('data-action');
      var params;

      $(target).find('.modal-title').html(title);
      $(target).find('form').first().attr('action', action);

      // Iterate over all checkboxes in the table
      t.$('input[type="checkbox"]').each(function() {
        // If checkbox doesn't exist in DOM
        if (!$.contains(document, this)) {
          // If checkbox is checked
          if (this.checked) {
            // Create a hidden element
            $form.append(
              $('<input>')
              .attr('type', 'hidden')
              .attr('name', this.name)
              .val(this.value)
            );
            // params[this.name] = this.value;
          }
        }
      });

      $.get(href, $form.serialize())
        .done(function(data) {
          $(target).find('.modal-body').html(data);
          $(target).modal('show');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          // console.log(jqXHR, textStatus, errorThrown);
          // alert('Server Error!');
          alert(jqXHR.responseJSON.error);
        });
    });
    $(document).on('submit', '.form-disabled-submit', function() {
      $(this).find("button[type=submit]").each(function(el) {
        $(this).prop('disabled', true);
      });
    });
    $('#myEditModal').on('hidden.bs.modal', function(e) {
      var $form = $(this).find('form').first();
      $form.attr('action', '#!');
      $(this).find('.modal-body').html('');
    });
  });
</script>
