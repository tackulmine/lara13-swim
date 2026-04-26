<script src="/assets/plugins/bs-custom-file-input/1.3.4/bs-custom-file-input.min.js"></script>
<script src="/assets/plugins/countdown/2.2.0/jquery.countdown.min.js"></script>
<script>
  // init global
  var ajaxCalling = false;
  var jqxhr = false;

  var getParticipants = function(selector) {
    var $target = $('select[name="name"]');
    var $school = $('[name="school"]');
    var params = {
      // name: $participant.val(),
      school: $school.val(),
    };
    // check requirement
    if ($school.val() != "" && !ajaxCalling) {
      ajaxCalling = true;
      // ajax call
      jqxhr = $.get("{{ route('competition.ajax-get-participants', $event->slug) }}", params)
        .done(function(data) {
          // console.log(data.results[0].id);
          if (data.results.length) {
            $target.html('');
            if ($target.find("option[value='']").length === 0) {
              var option = new Option('---', '', false, false);
              $target.append(option);
            }
            data.results.map(function(item) {
              // create the option and append to Select2
              var itemId = item.id.replace("\\", "");
              var itemVal = item.text.replace("\\", "");
              var option = new Option(itemVal, itemId, false, false);
              if (!$target.find("option[value='" + item.text + "']").length) {
                $target.append(option);
              }
            });
          }
        })
        .fail(function() {
          alert("Server error!");
        })
        .always(function() {
          ajaxCalling = false;
          $target.trigger('change');
        });
    }
  };

  var getParticipantDetail = function(selector) {
    var $school = $('[name="school"]');
    var $participant = $('select[name="name"]');
    var $birthYear = $('input[name="birth_year"]');
    var $gender = $('input[name="gender"]');
    var params = {
      name: $participant.val(),
      school: $school.val(),
    };
    // check requirement
    if ($school.val() != "" && !ajaxCalling) {
      ajaxCalling = true;
      // ajax call
      jqxhr = $.get("{{ route('competition.ajax-get-participant-detail', $event->slug) }}", params)
        .done(function(data) {
          // console.log(data);
          if (data.birth_year) {
            $birthYear.val(data.birth_year);
          }
          if (data.gender) {
            $('input[name="gender"][value="' + data.gender + '"]').prop("checked", true);
          }

          ajaxCalling = false;
          getTypeGaya('input[name="category"], input[name="gender"]');
          syncTypeGaya();
        })
        .fail(function() {
          alert("Server error!");
        })
        .always(function() {
          ajaxCalling = false;
        });
    }
  };

  var getTypeGaya = function(selector) {
    // set vars
    var $target = $('.style-checkboxes').find('.checkbox-list');

    var $participant = $('select[name="name"]');
    var $school = $('[name="school"]');
    var $category = $('input[name="category"]:checked');
    var $gender = $('input[name="gender"]:checked');
    var params = {
      name: $participant.val(),
      school: $school.val(),
      category: $category.val(),
      gender: $gender.val(),
    };
    // check requirement
    if ($category.length && $gender.length && !ajaxCalling) {
      ajaxCalling = true;
      // init
      $(selector).prop('disabled', true);
      $target.html('<em>Mencari Tipe Gaya sesuai pilihan Jenis Kelamin dan Kategori..</em>');
      // ajax call
      jqxhr = $.get("{{ route('competition.ajax-get-types', $event->slug) }}", params)
        .done(function(data) {
          $target.html(data);
          setTimeout(() => {
            syncTypeGaya();
          }, 500);
        })
        .fail(function() {
          alert("Server error!");
        })
        .always(function() {
          ajaxCalling = false;
          $(selector).prop('disabled', false);
        });
    }
  };

  var isEligibleCheck = function(catLabel, birthYear) {
    var catLabel, check, catLabelNumber, catLabelNumberRange, catLabelNumberUp, regex, catLabelNumberRangeArray =
      null;
    var birthStart, birthEnd = 0;
    // check matches case
    catLabelNumber = catLabel.replace(/\D/g, "");
    // console.log('catLabelNumber: '+catLabelNumber);
    regex = new RegExp(birthYear, 'gi');
    check = catLabelNumber.match(regex);

    birthYear = parseInt(birthYear);
    // console.log('birthYear: '+birthYear);

    if (catLabel.includes("-")) {
      // check range base
      catLabelNumberRange = catLabel.replace(/[^0-9-]/g, "");
      // console.log('catLabelNumberRange: '+catLabelNumberRange);
      catLabelNumberRangeArray = catLabelNumberRange.split("-");
      birthStart, birthEnd = 0;
      if (catLabelNumberRangeArray) {
        birthStart = parseInt(catLabelNumberRangeArray[0]);
        // console.log('birthStart: '+birthStart);
        birthEnd = parseInt(catLabelNumberRangeArray[1]);
        // console.log('birthEnd: '+birthEnd);
        if (birthYear >= birthStart && birthYear <= birthEnd) {
          check = true;
        }
      }
    }
    if (catLabel.includes("+")) {
      // check number up
      if (catLabelNumber) {
        catLabelNumberUp = parseInt(catLabelNumber);
        // console.log('catLabelNumberUp: '+catLabelNumberUp);
        if (birthYear >= catLabelNumberUp) {
          check = true;
        }
      }
    }

    return check;
  };

  var syncTypeGaya = function() {
    // set vars
    var $target = $('.category-radios').find('.radio-list');

    var $registerAs = $('input[name="register_as"]');
    var $selectedRegisterAs = $('input[name="register_as"]:checked');
    var $school = $('select[name="school"]');
    var $birthYear = $('input[name="birth_year"]');
    var $category = $('input[name="category"]');
    var $selectedCategory = $('input[name="category"]:checked');
    var $birthCertificate = $('input[name="birth_certificate"]');
    var $birthCertificateLabel = $birthCertificate.closest('.form-group').find('label.col-form-label');

    // check requirement
    if ($registerAs.length && $selectedRegisterAs.val() !== '') {
      $birthCertificateLabel.text('Upload SK / KTA');

      // triggering the category options
      $target.find('input[name="category"]').prop('disabled', true);
      $target.find('input[name="category"]').each(function(i) {
        catLabel = $(this).parent().find('.custom-control-label').text();
        if (catLabel.toLowerCase().includes($selectedRegisterAs.val().toLowerCase())) {
          $(this).prop('disabled', false);
        }
      });

    } else {
      $birthCertificateLabel.text('Upload Akta Kelahiran');

      if ($category.length && $birthYear.val() != '' && !ajaxCalling) {
        // ajaxCalling = true;
        // init
        birthYear = $birthYear.val();
        $target.find('input[name="category"]').prop('checked', false);
        if (isEligibleCheck($selectedCategory.parent().find('.custom-control-label').text(), birthYear)) {
          $selectedCategory.prop('checked', true);
        } else {
          $('.style-checkboxes').find('.checkbox-list').html(
            '<em>Pilih dahulu Jenis Kelamin dan Kategori..</em>');
        }
        $target.find('input[name="category"]').prop('disabled', true);
        // set vars
        var check = null;
        var matchCount = 0;
        // exec
        $target.find('input[name="category"]').each(function(i) {
          catLabel = $(this).parent().find('.custom-control-label').text();
          // console.log('catLabel: '+catLabel);
          check = null;
          if (catLabel) {
            check = isEligibleCheck(catLabel, birthYear);
          }
          if (check) {
            $(this).prop('disabled', false);
            matchCount++;
          }
        });
      }

      // handle if empty match
      // if (matchCount <= 0) {
      //   $category.prop('disabled', false);
      // }
    }

    if ($birthCertificate.prop('required')) {
      $birthCertificateLabel.append('<span class="text-danger">*</span>');
    }
  };

  var syncAktaFoto = function() {
    var $curCategory = $('input[name="category"]:checked');
    var curCategoryLabel = $curCategory.closest('.custom-radio').find('.custom-control-label:eq(0)').text();
    var target = 'input[name="birth_certificate"], input[name="photo"]';
    if (curCategoryLabel.search(/relay/i) > 0) {
      $(target).prop('required', false);
      // $(target).each(function() {
      $(target).closest('.form-group').addClass('d-none').find('label.col-form-label .text-danger').text('');
      // });
    } else {
      $(target).prop('required', true);
      // $(target).each(function() {
      $(target).closest('.form-group').removeClass('d-none').find('label.col-form-label .text-danger').text('*');
      // });
    }
  };

  $(document).ready(function() {
    bsCustomFileInput.init();

    // ## Ajax Gaya
    // set vars
    var $target = $('.style-checkboxes').find('.checkbox-list');
    var gayaSelector = 'input[name="category"], input[name="gender"]';
    // set disabled
    $target.prepend('<em>Pilih dahulu Jenis Kelamin dan Kategori..</em>');
    $target.find('[type=checkbox]').prop('disabled', true);

    getTypeGaya(gayaSelector);

    $(document).on('change', gayaSelector, function() {
      // init preparation
      // ajaxCalling = false;
      if (jqxhr) {
        jqxhr.abort();
      }
      getTypeGaya(gayaSelector);
      syncAktaFoto();
    });

    // ## Ajax Participants
    // set vars
    var $target = $('[name="name"]');
    var participantSelector = '[name="school"]';

    getParticipants(participantSelector);

    // $(document).on('blur', participantSelector, function() {
    //   // init preparation
    //   // ajaxCalling = false;
    //   if (jqxhr) {
    //     jqxhr.abort();
    //   }

    //   getParticipants(participantSelector);
    // });
    $(participantSelector).on('select2:select', function(e) {
      if (jqxhr) {
        jqxhr.abort();
      }

      getParticipants(participantSelector);
    });

    $target.on("select2:select", function(e) {
      if (jqxhr) {
        jqxhr.abort();
      }

      // console.log("select2:select", e);
      getParticipantDetail(participantSelector);
    });

    // ## Sync Categories
    // set vars
    var birthYearSelector = 'input[name="birth_year"]';

    syncTypeGaya();

    $(document).on('change', 'input[name="register_as"]', function() {
      console.log('input[name="register_as"] triggered!');
      syncTypeGaya();
      getParticipants(participantSelector);
    });

    $(document).on('blur', birthYearSelector, function() {
      // init preparation
      // ajaxCalling = false;
      if (jqxhr) {
        jqxhr.abort();
      }

      // console.log('birth year triggered!');

      syncTypeGaya();
    });

    // ## Countdown
    $('[data-countdown]').each(function() {
      var $this = $(this),
        finalDate = $(this).data('countdown');
      $this.countdown(finalDate, function(event) {
        $this.html(event.strftime('%H:%M:%S'));
      }).on('finish.countdown', function() {
        window.location.reload();
      });
    });
  });
</script>
{{-- START Disabled cz participant ddl auto complete --}}
{{-- Laravel Javascript Validation --}}
<script
  src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') . '?' . filemtime(PUBLIC_PATH('vendor/jsvalidation/js/jsvalidation.js')) }}">
</script>
{!! JsValidator::formRequest('App\Http\Requests\EventRegistrationRequest', '#event-registration') !!}
{{-- END Disabled cz participant ddl auto complete --}}

@if (!empty($registrationCoachName) && !empty($registrationCoachPhone))
  <script>
    // Call the dataTables jQuery plugin
    $(document).ready(function() {
      var t = $('#dataTableCustom').DataTable({
        // columnDefs: [{
        //   targets: 0,
        //   searchable: false,
        //   orderable: false,
        //   className: 'select-checkbox',
        //   // 'render': function (data, type, full, meta){
        //   //   return '<input type="checkbox" name="ids[]" value="' + $('<div/>').text(data).html() + '">';
        //   // }
        // }],
        initComplete: function() {
          this.api().columns([1, 2, 3, 4, 5, 6]).every(function() {
            var column = this;
            var selectHtml =
              '<select class="select2me" style="padding-right:20px;"><option value="">-- ' +
              $(column.footer()).text() + ' --</option></select>';
            var select = $(selectHtml)
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
        t.column(0, {
          search: 'applied',
          order: 'applied'
        }).nodes().each(function(cell, i) {
          cell.innerHTML = i + 1;
        });
      }).draw();
    });
  </script>
@endif
