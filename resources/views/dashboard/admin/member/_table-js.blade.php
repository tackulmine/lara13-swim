<script>
  const agent = @json($agent);
  $(document).ready(function() {
    var t = $('#dataTableCustom').DataTable({
      stateSave: true,
      responsive: agent.isMobile ?? false,
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
        this.api().columns([4]).every(function() {
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
            d = removeHTMLTags(d);
            select.append('<option value="' + d + '">' + d +
              '</option>');
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

    // new $.fn.dataTable.FixedHeader( t, {
    //     footer: false
    // } );

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
    $('#member-form').on('submit', function(e) {
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
  });
</script>
