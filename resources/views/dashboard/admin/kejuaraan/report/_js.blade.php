<script src="/assets/plugins/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
<script src="/assets/plugins/bootstrap-datepicker/1.10.0/locales/bootstrap-datepicker.id.min.js"></script>
{{-- <script src="/assets/back/vendor/chart.js/Chart.min.js"></script> --}}
<script src="//cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  var getUrlParamsObject = function() {
    const params = new URLSearchParams(window.location.search);
    const paramsObject = {};

    // Iterate over the parameters and build the object
    for (const [key, value] of params) {
      paramsObject[key] = value;
    }

    return paramsObject;
  }

  $(document).ready(function() {

    if ($('.data-chart').length && $('.data-chart tbody tr td').length > 1) {
      $('.data-chart').each(function() {
        $tableSel = $(this);
        const labels = [];
        const datas = [];
        $tableSel.find('tbody tr').each(function() {
          var $sel = $(this).find('td');
          labels.push($sel.eq(1).text() + ' (' + $sel.eq(2).text() + ')');
          datas.push(parseFloat($sel.eq(3).text().replace(':', '')));
        });
        // console.log('labels: '+labels.join(' | '));
        // console.log('datas: '+datas.join(' | '));
        var data = {
          labels: labels,
          datasets: [{
            // axis: 'y',
            label: $('#chart-label-' + $tableSel.attr("data-master-gaya")).text(),
            data: datas,
            fill: false,
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
          }]
        };
        var config = {
          type: 'line',
          data: data,
          options: {
            plugins: {
              legend: {
                labels: {
                  // This more specific font property overrides the global property
                  font: {
                    size: 16
                  }
                }
              },
              title: {
                display: true,
                text: $('#chart-title-' + $tableSel.attr("data-master-gaya")).text()
              }
            },
            // indexAxis: 'y',
            scales: {
              y: {
                beginAtZero: true,
                title: {
                  display: true,
                  text: 'Poin Limit'
                }
              },
              x: {
                title: {
                  display: true,
                  text: 'Kejuaraan (Tanggal)'
                }
              }
            }
          }
        };
        // === include 'setup' then 'config' above ===

        @if (request()->filled('print'))
          Chart.defaults.font.size = 24;
        @else
          Chart.defaults.font.size = 18;
        @endif
        var myChart = new Chart(
          document.getElementById('myChart-' + $tableSel.attr("data-master-gaya")),
          config
        );

        // After the chart renders (e.g., in an onComplete animation callback)
        // var chartDataURL = myChart.toDataURL('image/png');
        var chartImageBase64 = myChart.toBase64Image();

        $.post("{!! route($baseRouteName . 'index') . getQueryHttpBuilder('?') !!}", {
            'chart_image_data': chartImageBase64,
            'master_championship_gaya_id': $tableSel.attr("data-master-gaya"),
            '_token': '{{ csrf_token() }}'
          },
          function(data, textStatus, jqXHR) {
            console.log('msg: ' + data);
          }
        );
      });

    }
  });
</script>
