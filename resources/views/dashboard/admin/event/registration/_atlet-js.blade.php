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

  // var unique = function(array) {
  //   return $.grep(array, function(el, index) {
  //     return index === $.inArray(el, array);
  //   });
  // };

  var strip = function(html) {
    let doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || "";
  };

  // var initMask = function() {
  //   // $('.input-mask-time').mask('00:00:00');
  //   $(":input").inputmask();
  // };

  // var initToggleDisabled = function() {
  //   $(document).on("change", "[data-toggle-disabled-id]", function() {
  //     var $target = $(this);
  //     var $id = $target.data('toggle-disabled-id');
  //     var $toggle = $('#' + $id);
  //     $toggle.prop('disabled', !$target.prop('checked'));
  //   });
  // };

  var fixedRows = {!! json_encode($eventRelayCatsRet) !!};
  // console.log(fixedRows);
  var categoriesType = $.fn.dataTable.absoluteOrder(fixedRows);

  $(document).ready(function() {
    var dtFilterCols = [3, 4, 5, 6, 7, 8];
    // var dtPrintCols = ':visible';
    // var dtPrintCols = [0, ':visible'];
    var dtPrintCols = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    var t = $('#dataTableCustom').DataTable({
      responsive: agent.isMobile ?? false,
      stateSave: true,
      dom: 'Bfrtip',
      lengthMenu: [
        [10, 25, 50, 100, -1],
        ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']
      ],
      buttons: [
        'pageLength', {
          text: '<i class="fas fa-file-csv"></i> CSV',
          extend: 'csv',
          exportOptions: {
            columns: dtPrintCols,
            format: {
              body: function(data, row, column, node) {
                // console.log(data, row, column, node);
                // data.charAt(0).toUpperCase() + data.slice(1) :
                if ($(node).hasClass('jumlah-gaya')) {
                  data = removeHTMLTags(data);
                }
                if ($(node).hasClass('tagihan')) {
                  data = data.replace('.', '');
                }
                if ($(node).hasClass('no')) {
                  data = row + 1;
                }
                return data;
              }
            }
          }
        }, {
          text: '<i class="fas fa-file-excel"></i> Excel',
          extend: 'excel',
          exportOptions: {
            columns: dtPrintCols,
            format: {
              body: function(data, row, column, node) {
                // console.log(data, row, column, node);
                // data.charAt(0).toUpperCase() + data.slice(1) :
                if ($(node).hasClass('jumlah-gaya')) {
                  data = removeHTMLTags(data);
                }
                if ($(node).hasClass('tagihan')) {
                  data = data.replace('.', '');
                }
                if ($(node).hasClass('no')) {
                  data = row + 1;
                }
                return data;
              }
            }
          }
        }, {
          text: '<i class="fas fa-file-pdf"></i> PDF',
          extend: 'pdfHtml5',
          orientation: 'landscape',
          pageSize: 'LEGAL',
          exportOptions: {
            columns: dtPrintCols,
            format: {
              body: function(data, row, column, node) {
                // console.log(data, row, column, node);
                // data.charAt(0).toUpperCase() + data.slice(1) :
                if ($(node).hasClass('jumlah-gaya')) {
                  data = removeHTMLTags(data);
                }
                if ($(node).hasClass('no')) {
                  data = row + 1;
                }
                return data;
              }
            }
          }
        }, {
          text: '<i class="fas fa-print"></i> Print',
          extend: 'print',
          exportOptions: {
            columns: dtPrintCols,
            format: {
              body: function(data, row, column, node) {
                // console.log(data, row, column, node);
                // data.charAt(0).toUpperCase() + data.slice(1) :
                if ($(node).hasClass('jumlah-gaya')) {
                  data = removeHTMLTags(data);
                }
                if ($(node).hasClass('no')) {
                  data = row + 1;
                }
                return data;
              }
            }
          }
        }, {
          text: '<i class="fas fa-sync"></i> Reset Filter',
          action: function(e, dt, node, config) {
            dt.state.clear()
            window.location.reload();
          }
        }
        // , 'colvis'
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
        }
      }],
      "footerCallback": function(row, data, start, end, display) {
        var api = this.api(),
          data;

        // Total over all pages
        var colTotal = api
          .column(10)
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Total over all pages excluding rows with a certain class
        var colSearchTotal = api
          .column(10, {
            search: 'applied'
          }) // Apply DataTables search filter
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Total over this page
        var colCurrentPageTotal = api
          .column(10, {
            page: 'current'
          }) // Apply DataTables current page only
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Update footer
        $(api.column(10).footer()).html(
          formatNumber(colCurrentPageTotal) + ' dari ' + formatNumber(colSearchTotal) + ' (' + formatNumber(
            colTotal) + ')'
        );

        // var individualCalcTotal = 0;
        // var estafetCalcTotal = 0;
        // var calcTotal = 0;

        // api
        //   .column(10, {
        //     search: 'applied'
        //   }) // Apply DataTables search filter
        //   .nodes()
        //   .each(function(cell, i, obj) {
        //     // console.log(cell, i);
        //     if ($(cell).hasClass('tagihan-individual')) {
        //       individualCalcTotal += intVal(cell.innerHTML);
        //     }
        //     if ($(cell).hasClass('tagihan-estafet')) {
        //       estafetCalcTotal += intVal(cell.innerHTML);
        //     }
        //     calcTotal += intVal(cell.innerHTML);
        //     // cell.innerHTML = i + 1;
        //   });

        // // console.log(individualCalcTotal);
        // // console.log(estafetCalcTotal);
        // // console.log(calcTotal);

        // $('#tagihan-info .tagihan-info-individu').html(formatNumber(individualCalcTotal));
        // $('#tagihan-info .tagihan-info-estafet').html(formatNumber(estafetCalcTotal));
        // $('#tagihan-info .tagihan-info-total').html(formatNumber(calcTotal));

      },
      initComplete: function() {
        // Add select filter
        this.api().columns(dtFilterCols).every(function(index) {
          var column = this;
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

          if (index == 7) {
            var columns = [];
            column.data().unique().sort().each(function(d, j) {
              var txt = strip(d);
              txt = parseInt(txt);
              columns.push(txt);
            });
            columns.sort();
            uniqueCols = unique(columns);
            // console.log(uniqueCols);
            $(uniqueCols).each(function(i, val) {
              select.append('<option value="' + val + '">' + val +
                '</option>');
            });
          } else {
            column.data().unique().sort().each(function(d, j) {
              select.append('<option value="' + d + '">' + d +
                '</option>');
            });
          }
        });

        // Init select2
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

    // $('.select2me').each(function() {
    //   $(this).find('option').each(function() {
    //     // if ($(this).text() === searchPhrase) {
    //     // console.log($(this).text(), filters);
    //     if (jQuery.inArray($(this).text(), filters) !== -1) {
    //       // console.log('selected');
    //       $(this).prop('selected', true);
    //     } else {
    //       $(this).prop('selected', false);
    //     }
    //   })
    //   $(this).trigger('change.select2');
    // });

    // // rows
    // t.rows( { search:'applied' } ).data().each(function(value, index) {
    //     console.log(value, index);
    // });
    // columns
    // t.column(5, { search:'applied' } ).data().each(function(value, index) {
    //   console.log(value, index);
    // });

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

    // Handle merge school btn click
    $(document).on('click', '.btn-type-edit', function(e) {
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

      // // Iterate over all checkboxes in the table
      // t.$('input[type="checkbox"]').each(function() {
      //     // If checkbox doesn't exist in DOM
      //     if (!$.contains(document, this)) {
      //         // If checkbox is checked
      //         if (this.checked) {
      //             // Create a hidden element
      //             $form.append(
      //                 $('<input>')
      //                 .attr('type', 'hidden')
      //                 .attr('name', this.name)
      //                 .val(this.value)
      //             );
      //             // params[this.name] = this.value;
      //         }
      //     }
      // });

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

    // initMask();
    // initToggleDisabled();
  });

  // $(document).on("ajaxComplete", function() {
  //   initMask();
  // });
</script>
