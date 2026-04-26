<script src="/assets/plugins/datatables/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="/assets/plugins/datatables/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script>
  const agent = @json($agent);
  $(document).ready(function() {
    var t = $('#dataTableCustom').DataTable({
      responsive: agent.isMobile ?? false,
      stateSave: true,
      dom: 'Bfrtip',
      // columnDefs: [{
      //   targets: 2,
      //   type: 'num-html',
      //   render: function(data, type, row) {
      //     if (type === 'sort') {
      //       // Extract number and letter for sorting
      //       var match = data.match(/(\d+)(M\s[A-Z-\s])/);
      //       if (match) {
      //         console.log('match:' + match[1]);
      //         // return parseInt(match[1]) + match[2]; // e.g., "50A", "50B", "100A", "100B"
      //         return [parseInt(match[1]), match[2].trim()];
      //       }
      //     }
      //     return data; // Display original data
      //   }
      // }],
      lengthMenu: [
        [10, 25, 50, 100, -1],
        ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']
      ],
      buttons: [
        'pageLength', {
          text: '<i class="fas fa-sync"></i> Reset Filter',
          action: function(e, dt, node, config) {
            dt.state.clear()
            window.location.reload();
          }
        }
      ],
      initComplete: function() {
        this.api().columns([1, 2]).every(function() {
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
