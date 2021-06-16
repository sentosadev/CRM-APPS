<script>
  $(document).ready(function() {
    $('.select2').select2()
  })
</script>
<style>
  #leadsSourceEffectiveness {
    height: 420px;
  }

  #leadsToProspectJourney {
    width: 100%;
    height: 454px
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
    <div class="col-md-4">
      <!-- AREA CHART -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Leads Source Effectiveness</h3>
        </div>
        <div class="box-body">
          <input type="text" class="form-control" id='periodeLeadsSourceEffect' name='periodeLeadsSourceEffect' placeholder="Periode Leads Source Effectiveness">
          <input type="hidden" class="form-control" id='start_periodeLeadsSourceEffect'>
          <input type="hidden" class="form-control" id='end_periodeLeadsSourceEffect'>
          <script>
            $(function() {
              $('#periodeLeadsSourceEffect').daterangepicker({
                // opens: 'left',
                autoUpdateInput: false,
                locale: {
                  format: 'DD/MM/YYYY'
                }
              }, function(start, end, label) {
                $('#start_periodeLeadsSourceEffect').val(start.format('YYYY-MM-DD'));
                $('#end_periodeLeadsSourceEffect').val(end.format('YYYY-MM-DD'));
              }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
              }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#start_periodeLeadsSourceEffect').val('');
                $('#end_periodeLeadsSourceEffect').val('');
              });
            });
          </script>
          <div id="leadsSourceEffectiveness"></div>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Leads To Prospect Journey</h3>
        </div>
        <div class="box-body">
          <div id="leadsToProspectJourney"></div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Leads Performance Details</h3>
          <div class="box-tools pull-right">
            <button class='btn btn-primary btn-sm'>Export To Excel</button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table class='table table-condensed table-bordered table-striped serverside-tables' style="width:100%">
              <thead>
                <th width='5%'>#</th>
                <th width='15%'>Leads ID</th>
                <th>Nama</th>
                <th>Dealer Sebelumnya</th>
                <th>Dealer Dispatch</th>
                <th>Tgl. Dispatch</th>
                <th>Platform Data</th>
                <th>Source Leads</th>
                <th>Deskripsi Event</th>
                <th>Periode Event</th>
                <th>Status FU</th>
                <th>Pernah Terhubung</th>
                <th>Hasil FU</th>
                <th>Jumlah FU</th>
                <th>Next FU</th>
                <th>Last Update</th>
                <th>MD Overdue</th>
                <th>D Overdue</th>
              </thead>
            </table>
          </div>
          <!-- /.row -->
        </div>
      </div>
    </div>
  </div>
</section>
<?php $this->load->view('dashboard/leads_dashboard/leads_source_effectiveness'); ?>
<script>
  $(document).ready(function() {
    ajaxDataChartLeadsSourceEffectiveness();
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
</script>

<script>
  am4core.ready(function() {

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    var chart = am4core.create("leadsToProspectJourney", am4charts.SankeyDiagram);
    chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

    chart.data = [{
        from: "Not Invited",
        to: "Facebook",
        value: 1,
        id: "NI0"
      },
      {
        from: "Not Invited",
        to: "Ads",
        value: 1,
        id: "NI1"
      },
      {
        from: "Not Invited",
        to: "Invitation",
        value: 1,
        id: "NI2"
      },
      {
        from: "Not Invited",
        to: "Whatsapp",
        value: 1,
        id: "NI3"
      },

      {
        from: "Invited",
        to: "Facebook",
        value: 1,
        id: "IN0"
      },
      {
        from: "Invited",
        to: "Ads",
        value: 1,
        id: "IN1"
      },
      {
        from: "Invited",
        to: "Invitation",
        value: 5,
        id: "IN2"
      },
      {
        from: "Invited",
        to: "Whatsapp",
        value: 1,
        id: "IN3"
      },

      //
      {
        from: "Facebook",
        to: "RO Customer",
        value: 5,
        id: ""
      },
      {
        from: "Facebook",
        to: "New Customer",
        value: 1,
        id: ""
      },

      {
        from: "Ads",
        to: "RO Customer",
        value: 5,
        id: ""
      },
      {
        from: "Ads",
        to: "New Customer",
        value: 5,
        id: ""
      },

      {
        from: "Invitation",
        to: "RO Customer",
        value: 1,
        id: ""
      },

      {
        from: "Whatsapp",
        to: "RO Customer",
        value: 1,
        id: ""
      },

      {
        from: "Whatsapp",
        to: "New Customer",
        value: 1,
        id: ""
      },

      {
        from: "Invitation",
        to: "New Customer",
        value: 1,
        id: ""
      },

      {
        from: "RO Customer",
        to: "Hot Prospect",
        value: 2,
        id: ""
      },
      {
        from: "RO Customer",
        to: "Low Prospect",
        value: 2,
        id: ""
      },
      {
        from: "RO Customer",
        to: "Low Prospect",
        value: 2,
        id: ""
      },

      {
        from: "New Customer",
        to: "Low Prospect",
        value: 2,
        id: ""
      },
      {
        from: "New Customer",
        to: "Med Prospect",
        value: 1,
        id: ""
      },
      {
        from: "New Customer",
        to: "Low Prospect",
        value: 2,
        id: ""
      },
      {
        from: "New Customer",
        to: "Not Prospect",
        value: 2,
        id: ""
      },
      {
        from: "New Customer",
        to: "Need Follow Up",
        value: 2,
        id: ""
      },
      {
        from: "New Customer",
        to: "No Need Fol. Up",
        value: 2,
        id: ""
      },

    ];
    let hoverState = chart.links.template.states.create("hover");
    hoverState.properties.fillOpacity = 0.6;

    chart.dataFields.fromName = "from";
    chart.dataFields.toName = "to";
    chart.dataFields.value = "value";

    chart.links.template.propertyFields.id = "id";
    chart.links.template.colorMode = "solid";
    chart.links.template.fill = new am4core.InterfaceColorSet().getFor("alternativeBackground");
    chart.links.template.fillOpacity = 0.1;
    chart.links.template.tooltipText = "";

    // highlight all links with the same id beginning
    chart.links.template.events.on("over", function(event) {
      let link = event.target;
      let id = link.id.split("-")[0];

      chart.links.each(function(link) {
        if (link.id.indexOf(id) != -1) {
          link.isHover = true;
        }
      })
    })

    chart.links.template.events.on("out", function(event) {
      chart.links.each(function(link) {
        link.isHover = false;
      })
    })

    // for right-most label to fit
    chart.paddingRight = 100;

    // make nodes draggable
    var nodeTemplate = chart.nodes.template;
    nodeTemplate.inert = true;
    nodeTemplate.readerTitle = "Drag me!";
    nodeTemplate.showSystemTooltip = true;
    nodeTemplate.width = 20;

    // make nodes draggable
    var nodeTemplate = chart.nodes.template;
    nodeTemplate.readerTitle = "Click to show/hide or drag to rearrange";
    nodeTemplate.showSystemTooltip = true;
    nodeTemplate.cursorOverStyle = am4core.MouseCursorStyle.pointer

  }); // end am4core.ready()
</script>