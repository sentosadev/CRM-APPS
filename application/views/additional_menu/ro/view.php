<script>
  $(document).ready(function() {
    $('.select2').select2()
  })
</script>
<section class="content">
  <!-- SELECT2 EXAMPLE -->
  <div class="row">
    <div class="col-sm-12">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Filter Data</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form class="form-horizontal" id="form_filter">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Dealer</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='kode_dealer' name="kode_dealer[]" multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Periode</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="periode" name="periode" valuec="<?= date('d/m/Y') . ' - ' . date('d/m/Y') ?>" value="01/01/2021 - 31/01/2021">
                    </div>
                  </div>
                  <script>
                    $(function() {
                      $('#periode').daterangepicker({
                        // opens: 'left',
                        autoUpdateInput: false,
                        locale: {
                          format: 'DD/MM/YYYY'
                        }
                      }, function(start, end, label) {}).on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                      }).on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                      });
                    });
                  </script>
                </div>
              </form>
            </div>
            <div class="box-footer" align='center'>
              <button class='btn btn-primary' type="button" onclick="searchData(this)"><i class="fa fa-search"></i></button>
              <button class='btn btn-default' type="button" onclick="location.reload(true)"><i class="fa fa-refresh"></i></button>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <div class="box box-default" style='min-height:140px'>
        <div class="box-header with-border">
          <h3 class="box-title">Tabel Actual RO</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table class='table table-condensed table-bordered table-striped' style="width:100%" id="tables"></table>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <div class="box box-default" style='min-height:340px'>
        <div class="box-header with-border">
          <h3 class="box-title">Grafik Actual RO</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <canvas id="myChart" width="300" height="166"></canvas>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>
  <!-- /.box -->
</section>
<script>
  var set_tables = [];
  var set_conts = [];
  var tahun = [];

  function searchData(el) {
    var values = new FormData($('#form_filter')[0]);
    $.ajax({
      beforeSend: function() {
        set_tables = [];
        set_conts = [];
        tahun = [];
        $(el).html('<i class="fa fa-spinner fa-spin"></i>');
        setTables();
        // $(el).attr('disabled', true);
      },
      enctype: 'multipart/form-data',
      url: '<?= site_url(get_controller() . '/getData') ?>',
      type: "POST",
      data: values,
      processData: false,
      contentType: false,
      // cache: false,
      dataType: 'JSON',
      success: function(response) {
        $(el).html('<i class="fa fa-search"></i>');
        $(el).attr('disabled', false);
        if (response.status == 1) {
          for (x of response.tables) {
            set_tables.push(x);
          }
          for (x of response.conts) {
            set_conts.push(x);
          }
          tahun = response.tahun;
          setTables();
        } else {
          Swal.fire({
            icon: 'error',
            title: '<font color="white">Peringatan</font>',
            html: '<font color="white">' + response.pesan + '</font>',
            background: '#dd4b39',
            confirmButtonColor: '#cc3422',
            confirmButtonText: 'Tutup',
            iconColor: 'white'
          })
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: '<font color="white">Peringatan</font>',
          html: '<font color="white">Telah terjadi kesalahan !</font>',
          background: '#dd4b39',
          confirmButtonColor: '#cc3422',
          confirmButtonText: 'Tutup',
          iconColor: 'white'
        })
        $(el).html('<i class="fa fa-search"></i>');
        $(el).attr('disabled', false);
      }
    });
  }

  function setTables() {
    html = '';
    if (set_tables.length > 0) {
      colspan = tahun.length;
      html += "<thead>";
      html += "<tr>";
      html += "<th rowspan=2></th><th colspan=" + colspan + ">Own Dealer</th>";
      html += "<th colspan=" + colspan + ">Other Dealer</th>";
      html += "<th colspan=" + colspan + ">New Customer</th>";
      html += "<th rowspan=2>Total Penjualan</th>";
      html += "</tr>";
      html += "<tr>";
      for (let index = 1; index <= 3; index++) {
        for (dt of tahun) {
          html += "<th>" + dt + "</th>";
        }
      }
      html += "</tr>";
      html += "</thead>";
      html += "<tbody>";
      html += "<tr>";
      for (x of set_tables) {
        html += "<td>" + x + "</td>";
      }
      html += "</tr>";
      html += "<tr>";
      for (x of set_conts) {
        html += "<td>" + x + "</td>";
      }
      html += "</tr>";
      html += "</tbody>";
    }
    $("#tables").html(html);
    generateChart(set_tables);
  }
</script>

<script src="<?= base_url('assets/') ?>components/chart.js/Chart.js"></script>
<script>
  var chart_data = {};

  function generateChart(params) {
    var ctx = document.getElementById("myChart").getContext("2d");
    var labels = ['KPB 1', 'KPB 2', 'KPB 3', 'KPB 4'];
    var own = [];
    var other = [];
    var not_service = [];
    for (dt of params) {
      own.push(dt[2]);
      other.push(dt[4]);
      not_service.push(dt[6]);
    }
    var data = {
      labels: labels,
      datasets: [{
        label: "Own",
        backgroundColor: "#67b7dc",
        data: own
      }, {
        label: "Other",
        backgroundColor: "#dc6767",
        data: other
      }, {
        label: "Not Service",
        backgroundColor: "#838384",
        data: not_service
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

<script>
  $(document).ready(function() {
    $("#kode_dealer").select2({
      // minimumInputLength: 2,
      ajax: {
        url: "<?= site_url('api/private/dealer/selectDealer') ?>",
        type: "POST",
        dataType: 'json',
        delay: 100,
        data: function(params) {
          return {
            searchTerm: params.term, // search term
          };
        },
        processResults: function(response) {
          return {
            results: response
          };
        },
        cache: true
      }
    });
  });
</script>