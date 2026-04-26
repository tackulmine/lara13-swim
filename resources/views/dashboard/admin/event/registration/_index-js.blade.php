@php
  // dump($event->categories->toArray());
  $eventRelayCats = $event->categories()->where('name', 'LIKE', '%RELAY%')->pluck('name');
  $eventRelayCatsRet = [];
  foreach ($eventRelayCats as $cat) {
      $eventRelayCatsRet[] = [
          'value' => $cat,
          'position' => 'top',
      ];
  }
  // dump($eventRelayCatsRet);
  // dd(json_encode($eventRelayCatsRet));
@endphp
<script src="/assets/plugins/datatables/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="/assets/plugins/datatables/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="/assets/plugins/datatables/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="/assets/plugins/datatables/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="/assets/plugins/datatables/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="/assets/plugins/datatables/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="/assets/plugins/datatables/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="/assets/plugins/datatables/sorting/2.1.0/absolute.min.js"></script>
<script>
  const agent = @json($agent);
  // Call the dataTables jQuery plugin
  $(document).ready(function() {
    var dtFilterCols = [2, 3, 4, 5, 6, 7, 8, 9];
    // var categoriesType = $.fn.dataTable.absoluteOrder([{
    //   value: 'KU II (RELAY)',
    //   position: 'top'
    // }]);
    var fixedRows = {!! json_encode($eventRelayCatsRet) !!};
    // console.log(fixedRows);
    var categoriesType = $.fn.dataTable.absoluteOrder(fixedRows);
    var t = $('#dataTableCustom').DataTable({
      responsive: agent.isMobile ?? false,
      stateSave: true,
      dom: 'Bfrtip',
      lengthMenu: [
        [10, 25, 50, 100, -1],
        ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']
      ],
      buttons: [
        'pageLength',
        {
          text: '<i class="fas fa-file-csv"></i> CSV',
          extend: 'csv',
          exportOptions: {
            columns: ':visible',
            format: {
              body: function(data, row, column, node) {
                // console.log(data, row, column, node);
                return $(node).hasClass('no') ?
                  // data.charAt(0).toUpperCase() + data.slice(1) :
                  row + 1 :
                  data;
              }
            }
          }
        },
        {
          text: '<i class="fas fa-file-excel"></i> Excel',
          extend: 'excel',
          exportOptions: {
            columns: ':visible',
            format: {
              body: function(data, row, column, node) {
                // console.log(data, row, column, node);
                return $(node).hasClass('no') ?
                  // data.charAt(0).toUpperCase() + data.slice(1) :
                  row + 1 :
                  data;
              }
            }
          }
        },
        {
          text: '<i class="fas fa-file-pdf"></i> PDF',
          extend: 'pdfHtml5',
          orientation: 'landscape',
          pageSize: 'LEGAL',
          exportOptions: {
            columns: ':visible',
            format: {
              body: function(data, row, column, node) {
                // console.log(data, row, column, node);
                return $(node).hasClass('no') ?
                  // data.charAt(0).toUpperCase() + data.slice(1) :
                  row + 1 :
                  data;
              }
            }
          }
        },
        {
          text: '<i class="fas fa-print"></i> Print',
          extend: 'print',
          exportOptions: {
            columns: ':visible',
            format: {
              body: function(data, row, column, node) {
                // console.log(data, row, column, node);
                return $(node).hasClass('no') ?
                  // data.charAt(0).toUpperCase() + data.slice(1) :
                  row + 1 :
                  data;
              }
            }
          }
        },
        {
          text: '<i class="fas fa-sync"></i> Reset Filter',
          action: function(e, dt, node, config) {
            dt.state.clear()
            window.location.reload();
          }
        }, 'colvis'
      ],
      columnDefs: [{
        type: categoriesType,
        targets: 6
      }, {
        targets: 0,
        searchable: false,
        orderable: false,
        className: 'select-checkbox',
        'render': function(data, type, full, meta) {
          return '<input type="checkbox" name="ids[]" value="' + $('<div/>').text(
            data).html() + '">';
        },
      }],
      initComplete: function() {
        this.api().columns(dtFilterCols).every(function() {
          var column = this;
          // var select = $(
          //     '<select class="select2me" style="padding-right:20px;"><option value="">---</option></select>'
          //   )
          var select = $(
              '<div class="mb-2 mr-sm-2 d-block d-sm-inline-block w-100 w-sm-auto"><select class="form-control select2me" style="padding-right:20px;"><option value="">- ' +
              $(column.header()).text() + '-</option></select></div>'
            )
            // .appendTo($(column.footer()).empty())
            .appendTo('#dataTableCustom_filters')
            .children('select')
            .first()
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

    var filters = [];
    t.columns(dtFilterCols).every(function(index) {
      // console.log(index);
      uniqueData = t.column(index, {
        search: 'applied'
      }).data().unique();
      uniqueValueData = checkUniqueValues(uniqueData);
      // console.log('uniqueData: ');
      // console.log(uniqueValueData);
      // console.log('index: ' + index);
      // console.log('index length: ' + uniqueValueData.length);
      if (uniqueValueData.length === 1) {
        uniqueData.each(function(value, index) {
          value = removeHTMLTags(value);
          if (value !== '' && jQuery.inArray(value, filters) === -1) {
            filters.push(value);
          }
        });
      }

      // console.log(filters);
      // console.log('=== +++ ===');
    });

    $('.select2me').each(function() {
      $(this).find('option').each(function() {
        // if ($(this).text() === searchPhrase) {
        // console.log($(this).text(), filters);
        if (jQuery.inArray($(this).text(), filters) !== -1) {
          // console.log('selected');
          $(this).prop('selected', true);
        } else {
          $(this).prop('selected', false);
        }
      })
      $(this).trigger('change.select2');
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
    $('#participant-form').on('submit', function(e) {
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
