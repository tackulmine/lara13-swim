<script src="/assets/plugins/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
<script src="/assets/plugins/bootstrap-datepicker/1.10.0/locales/bootstrap-datepicker.id.min.js"></script>
{{-- <script src="/assets/back/vendor/chart.js/Chart.min.js"></script> --}}
<script src="//cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  $(document).ready(function() {

    if ($('#data-chart').length && $('#data-chart tbody tr td').length > 1) {
      const labels = [];
      $('#data-chart tbody tr').each(function() {
        labels.push($(this).find('td').eq(1).text() + ', W' + $(this).find('td').eq(2).text());
      });
      const datas = [];
      $('#data-chart tbody tr').each(function() {
        datas.push(parseFloat($(this).find('td').eq(3).text().replace(':', '')));
      });
      const data = {
        labels: labels,
        datasets: [{
          // axis: 'y',
          label: $('#chart-label').text(),
          data: datas,
          fill: false,
          backgroundColor: 'rgb(255, 99, 132)',
          borderColor: 'rgb(255, 99, 132)',
        }]
      };
      const config = {
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
              text: $('#chart-title').text()
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
                text: 'Periode (Bulan, Minggu)'
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
        document.getElementById('myChart'),
        config
      );
    }
  });
</script>
