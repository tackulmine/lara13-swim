<script>
  const agent = @json($agent);
  $(document).ready(function() {
    var dtFilterCols = [2];
    var t = $('#dataTableCustom').DataTable({
      responsive: agent.isMobile ?? false,
      stateSave: false,
      paging: false,
      // dom: 'Bfrtip',
      lengthMenu: [
        [-1, 10, 25, 50, 100],
        ['all', '10', '25', '50', '100']
      ],
      "footerCallback": function(row, data, start, end, display) {
        var api = this.api(),
          data;

        // Total over all pages
        var colTotal = api
          .column(3)
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Total over all pages excluding rows with a certain class
        var colSearchTotal = api
          .column(3, {
            search: 'applied'
          }) // Apply DataTables search filter
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Total over this page
        var colCurrentPageTotal = api
          .column(3, {
            page: 'current'
          }) // Apply DataTables current page only
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Update footer
        $(api.column(3).footer()).html(
          formatNumber(colCurrentPageTotal) + ' dari ' + formatNumber(colSearchTotal) + ' (' + formatNumber(
            colTotal) + ')'
        );
      },
      initComplete: function() {
        // Add select filter
        this.api().columns(dtFilterCols).every(function(index) {
          var column = this;
          var select = $(
              '<select class="form-control select2me" style="padding-right:20px;"><option value="">- ' +
              $(column.header()).text() + '-</option></select></div>'
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
              '</option>');
          });
        });

        // Init select2
        initSelectToMe();
      }
    });

    t.on('order.dt search.dt', function() {
      t.column(0, {
        search: 'applied',
        order: 'applied'
      }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();
  });
</script>
