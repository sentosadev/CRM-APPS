<script>
  $(document).ready(function() {
    $('.select2').select2()
  })
</script>
<style>
  #chartdiv {
    height: 450px;
  }
</style>
<script src="<?= base_url('assets/') ?>plugins/amcharts4/core.js"></script>
<script src="<?= base_url('assets/') ?>plugins/amcharts4/charts.js"></script>
<script src="<?= base_url('assets/') ?>plugins/amcharts4/plugins/sunburst.js"></script>
<script src="<?= base_url('assets/') ?>plugins/amcharts4/themes/animated.js"></script>
<section>
  <div class="main-header">
    <nav class="navbar navbar-static-top" style='margin-left:0px'>
      <div class="container">
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Platform Data</a></li>
            <li><a href="#">Event Description</a></li>
            <li><a href="#">Dealers</a></li>
            <li><a href="#">Kabupaten</a></li>
            <li><a href="#">Periode</a></li>
            <li><a href="#">Series</a></li>
            <li><a href="#">Tipe Motor</a></li>
            <!-- <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Separated link</a></li>
                <li class="divider"></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li> -->
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
    </nav>

  </div>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-6">
      <!-- AREA CHART -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Leads Source Effectiveness</h3>
        </div>
        <div class="box-body">
          <div id="chartdiv"></div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>
</section>
<?php $data['data'] = ['selectLeadsId', 'selectStatusFU', 'selectPlatformData', 'selectSourceLeads', 'selectDealerSebelumnya', 'selectAssignedDealer', 'selectDeskripsiEvent', 'selectJumlahFu'];
$this->load->view('additionals/dropdown_search_menu_leads_customer_data', $data); ?>

<?php $data['data'] = ['selectWarna', 'selectTipe'];
$this->load->view('additionals/dropdown_series_tipe', $data); ?>

<?php $this->load->view('manage_customer/leads_customer_data/modal_assign_reassign'); ?>

<script>
  $(document).ready(function() {
    var dataTable = $('.serverside-tables').DataTable({
      "processing": true,
      "serverSide": true,
      "scrollX": true,
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
          d.id_platform_data_multi = $('#id_platform_data').val()
          d.id_source_leads_multi = $('#id_source_leads').val()
          d.kode_dealer_sebelumnya_multi = $('#kodeDealerSebelumnya').val()
          d.assigned_dealer_multi = $('#searchAssignedDealer').val()
          d.kode_warna_multi = $('#id_warna').val()
          d.kode_type_motor_multi = $('#id_tipe').val()
          d.leads_id_multi = $('#leads_id').val()
          d.deskripsi_event_multi = $('#deskripsiEvent').val()
          d.id_status_fu_multi = $('#id_status_fu').val()
          d.jumlah_fu = $('#jumlah_fu').val()
          d.start_next_fu = $('#start_next_fu').val()
          d.end_next_fu = $('#end_next_fu').val()
          return d;
        },
      },
      "createdRow": function(row, data, index) {
        if (data[16] == 'Overdue') {
          $('td', row).eq(16).addClass('bg-red'); // 6 is index of column
        } else if (data[16] == 'On Track') {
          $('td', row).eq(16).addClass('bg-green'); // 6 is index of column
        }
        if (data[17] == 'Overdue') {
          $('td', row).eq(17).addClass('bg-red'); // 6 is index of column
        } else if (data[17] == 'On Track') {
          $('td', row).eq(17).addClass('bg-green'); // 6 is index of column
        }
      },
      "columnDefs": [{
          "targets": [0, 1, 2, 3, 4, 5, 6],
          "orderable": false
        },
        // {
        //   "targets": [0, 6, 7],
        //   "className": 'text-center'
        // },
        // {
        //   "targets": [3],
        //   "className": 'text-right'
        // },
        // // { "targets":[0],"checkboxes":{'selectRow':true}}
        // { "targets":[4],"className":'text-right'}, 
        // // { "targets":[2,4,5], "searchable": false } 
      ],
    });
  });
  /**
   * ---------------------------------------
   * This demo was created using amCharts 4.
   * 
   * For more information visit:
   * https://www.amcharts.com/
   * 
   * Documentation is available at:
   * https://www.amcharts.com/docs/v4/
   * ---------------------------------------
   */

  // Themes begin
  am4core.useTheme(am4themes_animated);
  // Themes end

  // create chart
  var chart = am4core.create("chartdiv", am4plugins_sunburst.Sunburst);
  chart.padding(0, 0, 0, 0);
  chart.radius = am4core.percent(98);

  chart.data = [{
      name: "Invited",
      children: [{
          name: "RO Dealer",
          value: 10000
        },
        {
          name: "RO Fincoy",
          value: 16000
        },
        {
          name: "Leads MD",
          value: 16000
        },
        {
          name: "WA",
          value: 16000
        },
        {
          name: "Line",
          value: 16000
        }
      ]
    },
    {
      name: "Not Invited",

      children: [{
          name: "1",
          value: 10000
        },
        {
          name: "2",
          value: 16000
        },
        {
          name: "3",
          value: 26000
        }
      ]
    }
  ];

  chart.colors.step = 2;
  chart.fontSize = 11;
  chart.innerRadius = am4core.percent(0);

  // define data fields
  chart.dataFields.value = "value";
  chart.dataFields.name = "name";
  chart.dataFields.children = "children";


  var level0SeriesTemplate = new am4plugins_sunburst.SunburstSeries();
  level0SeriesTemplate.hiddenInLegend = false;
  chart.seriesTemplates.setKey("0", level0SeriesTemplate)

  // this makes labels to be hidden if they don't fit
  level0SeriesTemplate.labels.template.truncate = true;
  level0SeriesTemplate.labels.template.hideOversized = true;
  level0SeriesTemplate.slices.template.tooltipText = `{name}-{value.formatDuration("hh 'hours' & mm 'mins.'")}`;


  level0SeriesTemplate.labels.template.adapter.add("rotation", (rotation, target) => {
    target.maxWidth = target.dataItem.slice.radius - target.dataItem.slice.innerRadius - 10;
    target.maxHeight = Math.abs(target.dataItem.slice.arc * (target.dataItem.slice.innerRadius + target.dataItem.slice.radius) / 2 * am4core.math.RADIANS);

    return rotation;
  })


  var level1SeriesTemplate = level0SeriesTemplate.clone();
  chart.seriesTemplates.setKey("1", level1SeriesTemplate)
  level1SeriesTemplate.fillOpacity = 0.75;
  level1SeriesTemplate.hiddenInLegend = true;


  var level2SeriesTemplate = level0SeriesTemplate.clone();
  chart.seriesTemplates.setKey("2", level2SeriesTemplate)
  level2SeriesTemplate.fillOpacity = 0.5;
  level2SeriesTemplate.hiddenInLegend = true;

  chart.legend = new am4charts.Legend();
</script>