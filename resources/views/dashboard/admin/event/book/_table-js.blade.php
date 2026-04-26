<script>
  var url = "{!! route($baseRouteName . 'set-ordering', $event->id) !!}";

  function updateOrder(id, order) {
    $.post(url, {
        _token: "{{ csrf_token() }}",
        _method: 'PUT',
        item_id: id,
        order: order
      })
      .done(function(data) {
        // console.log(data);
        window.location.reload();
      });
  }

  function updateOrderItems(items_id) {
    $('#reorder-form input[name="items_id"]').val(items_id);
  }

  function updateOrders(ids) {
    $.post(url, {
        _token: "{{ csrf_token() }}",
        _method: 'PUT',
        ids: ids
      })
      .done(function(data) {
        // console.log(data);
        window.location.reload();
      });
  }

  $(document).ready(function() {
    var dataTable = $('#dataTableReorder').DataTable({
      columnDefs: [{
        targets: 0,
        searchable: false,
        orderable: true,
      }],
      rowReorder: true,
      // rowReorder: {
      //   // selector: 'tr',
      //   update: true
      // },
      paging: false,
      "footerCallback": function(row, data, start, end, display) {
        var api = this.api(),
          data;

        // Total over all pages
        var colTotalLintasan = api
          .column(3)
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Update footer
        $(api.column(3).footer()).html(
          formatNumber(colTotalLintasan) + ' Lintasan'
        );

        // Total over all pages
        var colTotalSeri = api
          .column(4)
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Update footer
        $(api.column(4).footer()).html(
          formatNumber(colTotalSeri) + ' Seri'
        );
      },
    });

    dataTable.on('row-reorder', function(e, diff, edit) {
      dataTable.one('draw', function() {
        console.log('Redraw occurred at: ' + new Date().getTime());
        ids = [];
        dataTable.rows().every(function(rowIdx, tableLoop, rowLoop) {
          // console.log(rowIdx, this.data()['DT_RowId']);
          ids.push(this.data()['DT_RowId']);
        });
        console.log(ids.join());
        // updateOrders(ids);
        updateOrderItems(ids.join());
      });
    });
  });

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
</script>
