<script>
  $(document).ready(function() {
    var t = $('#dataTableCustom').DataTable({
      initComplete: function() {
        this.api().columns([3, 5, 6, 8, 9]).every(function() {
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
            select.append('<option value="' + d + '">' + d + '</option>')
          });
        });
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
