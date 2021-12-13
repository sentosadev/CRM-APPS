<script>
  $(document).ready(function() {
    $('.select2').select2()
  })
</script>
<style>
  #performanceOverview {
    height: 420px;
  }

  #leadsToProspectJourney {
    width: 100%;
    height: 454px
  }

  .padding-td {
    padding-right: 5px;
    text-align: center;
    width: 10%;
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
            <?php foreach ($filter_header as $key => $val) { ?>
              <li class='li-nav' onclick="setFilterHeader('<?= $key ?>')" id="hdr_<?= $key ?>"><a href="#"><?= $val['text'] ?></a></li>
            <?php } ?>
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
  <?php $user = user(); ?>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
      <table style='width:100%'>
        <tr>
          <?php if ($user->md_d!='d') { ?>
            <td class='padding-td'>
              <div class="small-box bg-gray">
                <div class="inner" style="min-height:135px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Semua sumber data customer yang berpotensi menghasilkan leads dan diundang ke dalam event">
                  <p>Data Source <br>&nbsp;</p>
                  <h3 class="card_view" id="data_source">&nbsp;</h3>
                </div>
                <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="data_source_persen">&nbsp;</div>
              </div>
            </td>
            <td class='padding-td'>
              <div class="small-box bg-gray">
                <div class="inner" style="min-height:135px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Menampilkan jumlah customer yang diundang dalam VE. % yang ditampilkan adalah perbandingan jumlah Invited dengan Data Source">
                  <p>Invited Pre-Event</p>
                  <h3 class="card_view" id="invited_pre_event">&nbsp;</h3>
                </div>
                <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="invited_pre_event_persen">&nbsp;</div>
              </div>
            </td>
            <td class='padding-td'>
              <div class="small-box bg-gray">
                <div class="inner" style="min-height:135px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Customer yang hadir dalam VE, melakukan interaksi pada website VE (seluruh CMS Source, kecuali Non CMS), dan telah terverifikasi pada backlog Verification and Classified Customer Leads Data. % yang ditampilkan adalah perbandingan jumlah Leads dengan Data Source">
                  <p>Leads Invited/Non Invited</p>
                  <h3 class="card_view" id="leads_invited_non_invited" style='min-width:136px'>&nbsp;</h3>
                </div>
                <div class="small-box-footer card_view_persen" style="color:black;font-weight:bold" id="leads_invited_non_invited_persen">&nbsp;</div>
              </div>
            </td>
            <td class='padding-td'>
              <div class="small-box bg-gray">
                <div class="inner" style="min-height:135px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Data Leads yang sudah diprobing, terhubung oleh PIC VE MD, dan siap didispatch ke Dealer. % yang ditampilkan adalah perbandingan jumlah Contacted Leads dengan Leads">
                  <p>Contacted Leads <br>&nbsp;</p>
                  <h3 class="card_view" id="contact_leads">&nbsp;</h3>
                </div>
                <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="contact_leads_persen">&nbsp;</div>
              </div>
            </td>
            <td class='padding-td'>
              <div class="small-box bg-gray">
                <!-- Tooltip Dealer Belum -->
                <div class="inner" style="min-height:135px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Data Leads yang setelah dilakukan probing oleh PIC VE MD tertarik melakukan pembelian dengan kriteria Status Prospect berdasarkan tanggal Next Follow Up: Low (>1bulan), Medium (2-4minggu), Hot (<2minggu) % yang ditampilkan adalah perbandingan jumlah Prospects dengan Contacted Leads">
                  <p>Prospects <br>&nbsp;</p>
                  <h3 class="card_view" id="prospects">&nbsp;</h3>
                </div>
                <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="prospects_persen">&nbsp;</div>
              </div>
            </td>
          <?php } ?>
          <td class='padding-td'>
            <div class="small-box bg-gray">
              <!-- Tooltip Dealer Belum -->
              <div class="inner" style="min-height:135px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Data Prospects yang di follow up oleh PIC Salespeople Dealer. % yang ditampilkan adalah perbandingan jumlah Contacted Prospects dengan Prospects">
                <p>Contacted Prospects</p>
                <h3 class="card_view" id="contacted_prospects">&nbsp;</h3>
              </div>
              <div class="small-box-footer card_view_persen" style="color:black;font-weight:bold" id="contacted_prospects_persen">&nbsp;</div>
            </div>
          </td>
          <td class='padding-td'>
            <div class="small-box bg-gray">
              <div class="inner" style="min-height:135px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Prospect yang tertarik, siap untuk membeli unit Honda, dan sudah memiliki nomor SPK. % yang ditampilkan adalah perbandingan jumlah Deal dengan Contacted Prospects">
                <p>Deal <br>&nbsp;</p>
                <h3 class="card_view" id="deal">&nbsp;</h3>
              </div>
              <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="deal_persen">&nbsp;</div>
            </div>
          </td>
          <td class='padding-td'>
            <div class="small-box bg-gray">
              <div class="inner" style="min-height:135px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Prospect yang tertarik, siap untuk membeli unit Honda, dan sudah memiliki nomor SPK Sales. % yang ditampilkan adalah perbandingan jumlah Sales dengan Jumlah Deal">
                <p>Sales <br>&nbsp;</p>
                <h3 class="card_view" id="sales">&nbsp;</h3>
              </div>
              <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="sales_persen">&nbsp;</div>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>
  <?php echo '';/* ?>
  <div class="row">
    <div class="col-md-5">
      <!-- AREA CHART -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Performance Overview</h3>
        </div>
        <div class="box-body">
          <div id="performanceOverview"></div>
        </div>
      </div>
    </div>
    <div class="col-md-7">
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Prospect</h3>
        </div>
        <div class="box-body">
          <div id="prospect"></div>
          <div id="prospect_acc"></div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>
  <?php */ ?>
  <?php $this->load->view('dashboard/performance_dashboard/leads_funneling'); ?>
</section>
<?php $this->load->view('dashboard/performance_dashboard/performance_overview'); ?>
<?php $this->load->view('dashboard/performance_dashboard/prospect'); ?>
<?php
$data['filter_header'] = $filter_header;
$this->load->view('dashboard/performance_dashboard/modal_filter_header', $data); ?>

<script>
  function loadCardViewMD() {
    $.ajax({
      beforeSend: function() {
        $('.card_view').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.card_view_persen').html('<i class="fa fa-spinner fa-spin"></i>');
      },
      enctype: 'multipart/form-data',
      url: '<?= site_url(get_controller() . '/loadCardViewMD') ?>',
      type: "POST",
      data: filter_values,
      // processData: false,
      // contentType: false,
      // cache: false,
      dataType: 'JSON',
      success: function(response) {
        if (response.status == 1) {
          data = response.data;
          $('.card_view_persen').each(function() {
            id = this.id;
            $('#' + id).html(data[id]);
          });
          $('.card_view').each(function() {
            id = this.id;
            $('#' + id).html(data[id]);
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: '<font color="white">Peringatan</font>',
          html: '<font color="white">Telah terjadi kesalahan Saat Melakukan Load Card View!</font>',
          background: '#dd4b39',
          confirmButtonColor: '#cc3422',
          confirmButtonText: 'Tutup',
          iconColor: 'white'
        })
      }
    });
  }

  $(document).ready(function() {
    loadCardViewMD();
    loadLeadsFunneling();
  })
</script>