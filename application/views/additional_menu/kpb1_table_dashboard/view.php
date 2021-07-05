<script>
  $(document).ready(function() {
    $('.select2').select2()
  })
</script>
<section class="content">
  <!-- SELECT2 EXAMPLE -->
  <div class="row">
    <div class="col-sm-5">
      <div class="box box-default" style='min-height:400px'>
        <div class="box-header with-border">
          <h3 class="box-title">Tabel</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="form-horizontal">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group" id="filter_periode_ssu">
                  <label class="col-sm-3 control-label">Periode</label>
                  <div class="form-input">
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="periode_ssu" name="periode_ssu" value="<?= date('d/m/Y') . ' - ' . date('d/m/Y') ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <table class='table table-condensed table-bordered table-striped serverside-tables' style="width:100%">
            <thead>
              <th width='5%'>#</th>
              <th>Kode Dealer</th>
              <!-- <th>Nama Dealer</th> -->
              <th>SSU</th>
              <th>KPB 1 Return</th>
              <th>%</th>
            </thead>
          </table>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>
    </div>
    <div class="col-sm-7">
      <div class="box box-default" style='min-height:400px'>
        <div class="box-header with-border">
          <h3 class="box-title">Grafik</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div id="chartdiv" style="min-height:514px"></div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>
    </div>
    <!-- /.box -->
</section>
<script src="<?= base_url('assets/') ?>plugins/amcharts4/core.js"></script>
<script src="<?= base_url('assets/') ?>plugins/amcharts4/charts.js"></script>
<script src="<?= base_url('assets/') ?>plugins/amcharts4/plugins/sunburst.js"></script>
<script src="<?= base_url('assets/') ?>plugins/amcharts4/themes/animated.js"></script>
<script>
  var chart_data = {};
  $(document).ready(function() {
    var dataTable = $('.serverside-tables').DataTable({
      "processing": true,
      "serverSide": true,
      // "scrollX": true,
      "language": {
        "infoFiltered": "",
        "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
      },
      "order": [],
      "lengthMenu": [
        [10, 25, 50, 75, 100],
        [10, 25, 50, 75, 100]
      ],
      "ajax": {
        url: "<?php echo site_url(get_controller() . '/fetchData'); ?>",
        type: "POST",
        dataSrc: "data",
        data: function(d) {
          d.periode_ssu = $('#periode_ssu').val()
          return d;
        }
      },
      "drawCallback": function(settings) {
        generateChart(settings.json.data);
        //do whatever  
      },
      "columnDefs": [{
          "targets": [0, 1],
          "orderable": false
        },
        // {
        //   "targets": [1],
        //   "className": 'text-center'
        // },
        {
          "targets": [2, 3, 4],
          "className": 'text-right'
        },
        // // { "targets":[0],"checkboxes":{'selectRow':true}}
        // { "targets":[4],"className":'text-right'}, 
        // // { "targets":[2,4,5], "searchable": false } 
      ],
    });
    // ajaxChartData();
  });

  function search() {
    $('.serverside-tables').DataTable().ajax.reload();
  }

  $(function() {
    $('#periode_ssu').daterangepicker({
      // opens: 'left',
      autoUpdateInput: false,
      locale: {
        format: 'DD/MM/YYYY'
      }
    }, function(start, end, label) {}).on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
      search();
    }).on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
  });
</script>

<script>
  function generateChart(datas) {
    var new_data = [];
    for (x of datas) {
      dt = {
        kode_dealer_md: x[1],
        ssu: x[2],
        kpb1_return: x[3],
      }
      new_data.push(dt);
    }
    am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end



      var chart = am4core.create('chartdiv', am4charts.XYChart)
      chart.colors.step = 2;

      chart.legend = new am4charts.Legend()
      chart.legend.position = 'bottom'
      chart.legend.paddingBottom = 20
      chart.legend.labels.template.maxWidth = 95

      var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())
      xAxis.dataFields.category = 'kode_dealer_md'
      xAxis.renderer.cellStartLocation = 0.1
      xAxis.renderer.cellEndLocation = 0.9
      xAxis.renderer.grid.template.location = 0;

      var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
      yAxis.min = 0;

      function createSeries(value, name) {
        var series = chart.series.push(new am4charts.ColumnSeries())
        series.dataFields.valueY = value
        series.dataFields.categoryX = 'kode_dealer_md'
        series.name = name

        series.events.on("hidden", arrangeColumns);
        series.events.on("shown", arrangeColumns);

        var bullet = series.bullets.push(new am4charts.LabelBullet())
        bullet.interactionsEnabled = false
        bullet.dy = 30;
        bullet.label.text = '{valueY}'
        bullet.label.fill = am4core.color('#ffffff')

        return series;
      }

      chart.data = new_data;
      console.log(chart.data);


      createSeries('ssu', 'SSU');
      createSeries('kpb1_return', 'KPB 1 Return');

      function arrangeColumns() {

        var series = chart.series.getIndex(0);

        var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
        if (series.dataItems.length > 1) {
          var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
          var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
          var delta = ((x1 - x0) / chart.series.length) * w;
          if (am4core.isNumber(delta)) {
            var middle = chart.series.length / 2;

            var newIndex = 0;
            chart.series.each(function(series) {
              if (!series.isHidden && !series.isHiding) {
                series.dummyData = newIndex;
                newIndex++;
              } else {
                series.dummyData = chart.series.indexOf(series);
              }
            })
            var visibleCount = newIndex;
            var newMiddle = visibleCount / 2;

            chart.series.each(function(series) {
              var trueIndex = chart.series.indexOf(series);
              var newIndex = series.dummyData;

              var dx = (newIndex - trueIndex + middle - newMiddle) * delta

              series.animate({
                property: "dx",
                to: dx
              }, series.interpolationDuration, series.interpolationEasing);
              series.bulletsContainer.animate({
                property: "dx",
                to: dx
              }, series.interpolationDuration, series.interpolationEasing);
            })
          }
        }
      }

    }); // end am4core.ready()
  }
</script>