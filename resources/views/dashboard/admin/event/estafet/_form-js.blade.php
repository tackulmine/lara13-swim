<script>
  var ajaxEventSessionCall = function(t, sel) {
    var $target = $(sel);
    var eventSessionId = $('select[name="event_session_id"]').val() ?? $('select[name="event_session_id"]').attr(
      'data-selected-value');
    // console.log('init eventSessionId: ' + eventSessionId);
    $.get("{{ route($baseRouteName . 'ajax-get-event-session', $id) }}", {
        'event_stage_id': $(t).val() ?? $(t).attr('data-selected-value'),
        'event_session_id': eventSessionId,
      },
      function(data, textStatus, jqXHR) {
        // console.log('ajax-get-event-session:');
        // console.log('data: ' + data);
        // console.log('textStatus: ' + textStatus);

        // Clear existing options
        $target.empty();
        // Optional: add a placeholder option
        $target.append(new Option('-- Pilih --', '', false, false));

        var reset = true;

        if (Array.isArray(data)) {
          // console.log('Array: ');
          $.each(data, function(i, el) {
            // console.log('index: ' + i);
            // console.log('item: ' + el);

            if (parseInt(el.id) == parseInt(eventSessionId)) {
              reset = false;
              var newOption = new Option(el.session, el.id, true, true);
            } else {
              var newOption = new Option(el.session, el.id, false, false);
            }

            $target.append(newOption);
          });
        } else {
          // console.log('Object: ');
          $.each(data, function(id, value) {
            // console.log('id: ' + id);
            // console.log('value: ' + value);
            // console.log('eventSessionId: ' + eventSessionId);

            if (parseInt(id) == parseInt(eventSessionId)) {
              reset = false;
              var newOption = new Option(value, id, true, true);
            } else {
              var newOption = new Option(value, id, false, false);
            }
            $target.append(newOption);
          });
        }

        if (reset) {
          $target.val(null).trigger('change');
        }
      }
    ).fail(function(jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
  }

  var ajaxEventSessionParticipantCall = function(t, sel) {
    var $target = $(sel);
    $.get("{{ route($baseRouteName . 'ajax-get-event-session-participant', $id) }}", {
        'event_stage_id': $('select[name="event_stage_id"]').val(),
        'event_session_id': $(t).val() ?? $(t).attr('data-selected-value'),
      },
      function(data, textStatus, jqXHR) {
        // console.log('ajax-get-event-session-participant:');
        // console.log('data: ' + data);
        // console.log('textStatus: ' + textStatus);

        $target.html(data);
      }
    ).fail(function(jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
  }

  $(document).ready(function() {
    if (parseInt($('select[name="event_stage_id"]').val()) > 0) {
      ajaxEventSessionCall('select[name="event_stage_id"]', 'select[name="event_session_id"]');
    }
    if (parseInt($('select[name="event_session_id"]').val()) > 0 ||
      parseInt($('select[name="event_session_id"]').attr('data-selected-value')) > 0
    ) {
      ajaxEventSessionParticipantCall('select[name="event_session_id"]', '#estafet-table');
    }

    $(document).on('change', 'select[name="event_stage_id"]', function(el) {
      var $target = $('select[name="event_session_id"]');
      if (parseInt($(this).val()) > 0) {
        ajaxEventSessionCall($(this), 'select[name="event_session_id"]');
      } else {
        // Clear existing options
        $target.empty();
        // Optional: add a placeholder option
        $target.append(new Option('-- Pilih --', '', false, false));
        // Reset selected option
        $target.val(null).trigger('change');
      }
    });

    $(document).on('change', 'select[name="event_session_id"]', function(el) {
      var $target = $('#estafet-table');
      if (parseInt($(this).val()) > 0) {
        ajaxEventSessionParticipantCall($(this), '#estafet-table');
      } else {
        // Reset html target
        $target.html('');
      }
    });
  });
</script>
