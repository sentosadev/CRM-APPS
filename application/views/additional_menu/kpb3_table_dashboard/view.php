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
              <th>KPB 3 Return</th>
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
          <canvas id="myChart" width="400" height="346"></canvas>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>
  <!-- /.box -->
</section>
<script src="<?= base_url('assets/') ?>components/chart.js/Chart.js"></script>
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
        [10, 25, 50, 75, 100, -1],
        [10, 25, 50, 75, 100, 'All']
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
  function generateChart(params) {
    var ctx = document.getElementById("myChart").getContext("2d");
    var labels = [];
    var ssu = [];
    var kpb1 = [];
    for (dt of params) {
      labels.push(dt[1]);
      ssu.push(dt[2]);
      kpb1.push(dt[3]);
    }
    var data = {
      labels: labels,
      datasets: [{
        label: "SSU",
        backgroundColor: "#67b7dc",
        data: ssu
      }, {
        label: "KPB 3 Return",
        backgroundColor: "#dc6767",
        data: kpb1
      }]
    };

    var myBarChart = new Chart(ctx, {
      type: 'bar',
      data: data,
      options: {
        legend: {
          position: 'bottom'
        },
        barValueSpacing: 20,
        scales: {
          yAxes: [{
            ticks: {
              min: 0,
            }
          }]
        },
      }
    });
  }
</script>