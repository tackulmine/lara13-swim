<script>
  $(document).ready(function() {
    // Handle click on "Select all" control
    $(document).on('click', '#dataTablesCheckbox', function() {
      // Check/uncheck checkboxes for all rows in the table
      $('#masterparticipant-table input[type="checkbox"]').prop('checked', this.checked);
    });

    // Handle form submission event
    $('#school-form').on('submit', function(e) {
      var form = this;

      if (confirm(
          'Yakin menghapus semua data? \n\nHATI-HATI!! Data yang terhapus tidak akan bisa dikembalikan!'
        )) {
        // Iterate over all checkboxes in the table
        // console.log('checkboxes:', $('#masterparticipant-table tbody input[type="checkbox"]').length);
        $('#masterparticipant-table tbody input[type="checkbox"]').each(function() {
          // If checkbox is checked
          if ($(this).is(':checked')) {
            // Create a hidden element
            $(form).append(
              $('<input>')
              .attr('type', 'hidden')
              .attr('name', 'ids[]')
              .val($(this).closest('tr').attr('id'))
            );
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

      $(target).find('.modal-title').html(title);
      $(target).find('form').first().attr('action', action);

      // Iterate over all checkboxes in the table
      // console.log('checkboxes:', $('#masterparticipant-table tbody input[type="checkbox"]').length);
      $('#masterparticipant-table tbody input[type="checkbox"]').each(function() {
        // If checkbox is checked
        if ($(this).is(':checked')) {
          // Create a hidden element
          $form.append(
            $('<input>')
            .attr('type', 'hidden')
            .attr('name', 'ids[]')
            .val($(this).closest('tr').attr('id'))
          );
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

    $(document).on('init.dt', function(e, settings) {
      var api = new $.fn.dataTable.Api(settings);
      var state = api.state.loaded();

      // ... use `state` to restore information
      // console.log('state');
      // console.log(state);
      if (state !== null) {
        $('#masterparticipant-table thead tr.filter th').each(function(i) {
          if (i >= 1 && i <= 4) {
            $(this).find('input[type="search"]').val(state.columns[i].search.search);
          }
        });
      }
    });

    $(document).on('click', '.dt-buttons .buttons-reset', function() {
      $('#masterparticipant-table thead tr.filter th input[type="search"]').each(function(i) {
        $(this).val('');
      });
    });
  });
</script>
