@push('js')
  <script>
    var tableClone = function() {
      let $table = $('#table-clone');
      let $tbody = $table.find('tbody');
      let $tableItem = $table.find('.table-item');

      // destroy select2
      $tableItem.find('select').each(function(el) {
        if ($(this).hasClass('select2-hidden-accessible')) {
          $(this).select2('destroy');
        }
      });

      let clonedItem = $tableItem.find('tr').clone();

      // remove disabled
      clonedItem.find('select,input,textarea').prop('disabled', false);

      // reformat select2
      var $selector = clonedItem.find('select');
      $selector.each(function() {
        var prevSelectedVal = $tbody.find('tr:last').find('select[name="' + $(this).attr('name') +
          '"] option:selected').val();
        if (prevSelectedVal !== '' && prevSelectedVal !== null) {
          $(this).val(prevSelectedVal);
        }
      });
      $selector.select2({
        theme: 'bootstrap4',
      }).trigger('change');

      // append cloned item
      $tbody.append(clonedItem);
      // re-mask
      initMask();
    };

    var reSyncTableCloneBtn = function() {
      let $table = $('#table-clone');
      let $tbody = $table.find('tbody');
      $tbody.find('tr .add-item, tr .del-item').show();
      $tbody.find('tr:not(:last-child) .add-item').hide();

      if ($tbody.find('tr').length === 1) {
        $tbody.find('tr .del-item').hide();
      }
    };

    $(function() {
      if ($('#table-clone').length) {
        // delete item
        $(document).on('click', '#table-clone .del-item', function(e) {
          e.preventDefault();

          var $selector = $(this).closest('tr');
          if ($('#table-clone tbody tr').length > 1) {
            var itemEmptyFlag = true;
            var itemsVal = [];
            $selector.find('select,input,textarea').each(function(index, element) {
              // Code to execute for each element
              if ($(this).val() !== '' && $(this).val() !== null) {
                itemEmptyFlag = false;
                itemsVal.push($(this).attr('name') + '-' + $(this).val());
              }
            });
            // alert('itemEmptyFlag: ' + itemEmptyFlag + ' | itemsVal: ' + itemsVal.join(', '));
            if (!itemEmptyFlag) {
              if (confirm('Yakin menghapus?')) {
                $selector.remove();
                reSyncTableCloneBtn();
              }
            } else {
              $selector.remove();
              reSyncTableCloneBtn();
            }
          }
        });

        // add item
        $(document).on('click', '#table-clone .add-item', function(e) {
          e.preventDefault();

          tableClone();
          reSyncTableCloneBtn();
        });

        if ($('#table-clone tbody tr').length <= 0) {
          // init
          tableClone();
        }
        reSyncTableCloneBtn();
      }
    });
  </script>
@endpush
