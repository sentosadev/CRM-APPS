  <?php $disabled = '';
  $dt = ['disabled' => $disabled, 'row' => $row];
  ?>
  <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/floating-button/css/st.action-panel.css">
  <script src="<?= base_url('assets/') ?>plugins/floating-button/js/st.action-panel.js"></script>
  <section class="content">
    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="<?= site_url(get_slug()) ?>">
            <button class="btn bg-red btn-flat"><i class="fa fa-back"></i> Kembali</button>
          </a>
        </h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="form-horizontal">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#data_registrasi" aria-expanded="true">Data Registrasi</a></li>
              <li><a href="#pengajuan_kontak_sales" aria-expanded="false">Pengajuan & Kontak Sales</a></li>
              <li><a href="#data_pendukung_probing_1" aria-expanded="false">Data Pendukung & Probing 1</a></li>
              <?php if (count($list_follow_up) > 0) {
                $tot_tab_fol = ceil(count($list_follow_up) / 2);
                for ($i = 1; $i <= $tot_tab_fol; $i++) { ?>
                  <li><a href="#data_follow_up_<?= $i ?>" aria-expanded="false">Data Pendukung & Probing <?= $i + 1 ?></a></li>
                <?php }
              } else {
                $tot_tab_fol = 1; ?>
                <li><a href="#data_follow_up_1" aria-expanded="false">Data Pendukung & Probing 2</a></li>
              <?php } ?>
            </ul>
            <div class="tab-content">
              <?php $this->load->view('manage_customer/leads_customer_data/edit_tab_data_registrasi', $dt); ?>
              <?php $this->load->view('manage_customer/leads_customer_data/edit_tab_pengajuan_kontak_sales', $dt); ?>
              <?php $this->load->view('manage_customer/leads_customer_data/edit_tab_data_pendukung_probing_1', $dt); ?>
              <?php
              $dt['tot_tab_fol'] = $tot_tab_fol;
              $this->load->view('manage_customer/leads_customer_data/edit_tab_data_follow_up', $dt); ?>
            </div>
            <!-- /.tab-content -->
          </div>
        </div>
      </div>
      <!-- /.box-body -->

    </div>
    <!-- /.box -->
  </section>
  <div class="st-actionContainer left-bottom">
    <div class="st-panel">
      <div class="st-panel-header" style='text-align:center'>Follow Up Guidance</div>
      <div class="st-panel-contents">
        <table class='table table-condensed table-bordered'>
          <tr>
            <td>Pekerjaan</td>
            <td><span id='fug_deskripsiPekerjaan'><?= $row->deskripsiPekerjaan ?></span></td>
          </tr>
          <tr>
            <td>Golden Time</td>
            <td><span id="fug_golden_time"><?= $row->golden_time ?></span></td>
          </tr>
          <tr>
            <td>Script Guide</td>
            <td><span id='fug_script_guide'><?= $row->script_guide ?></span></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="st-btn-container right-bottom">
      <div class="st-button-main" id='btnFollowUpGuidance'>Follow Up Guidance</div>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      $('st-actionContainer').launchBtn({
        openDuration: 250,
        closeDuration: 150
      });
      changeTabs('data_follow_up_1') //Testing Menuju Tab Tertentu
    });

    function changeTabs(tabs) {
      $('[href="#' + tabs + '"]').tab('show');
      // document.body.scrollTop = 0; // For Safari
      // document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
      title = "Nama Customer : " + $('#nama').val() + "<br>" + "Kode Motor : " + $('#id_tipe').val() + "<br>" + "Tipe Motor : " + $('#id_warna').val() + "<br>" + "Riding Test : " + "<br>" + "Promo Diminati : " + $('#promoYangDiminatiCustomer').val();
      console.log(title);
      $('#tooltipInformasiCustomer').removeAttr('title');
      $('#tooltipInformasiCustomer').attr('title', title);
      $('#tooltipInformasiCustomer').attr('data-original-title', title);
    }

    $("#kodePekerjaan").on("change", function() {
      var values = {
        id: $(this).val()
      }
      $.ajax({
        beforeSend: function() {
          $('#btnFollowUpGuidance').html('<i class="fa fa-spinner fa-spin"></i>');
        },
        enctype: 'multipart/form-data',
        url: '<?= site_url(get_controller() . '/refreshContentFollowUpGuidance') ?>',
        type: "POST",
        data: values,
        // processData: false,
        // contentType: false,
        cache: false,
        dataType: 'JSON',
        success: function(response) {
          if (response.status == 1) {
            dt = response.data;
            $('#fug_script_guide').html(dt.script_guide);
            $('#fug_golden_time').html(dt.golden_time);
            $('#fug_deskripsiPekerjaan').html(dt.pekerjaan);
          }
          $('#btnFollowUpGuidance').attr('disabled', false);
          $('#btnFollowUpGuidance').html('Follow Up Guidance');
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
          $('#btnFollowUpGuidance').html('Follow Up Guidance');
          $('#btnFollowUpGuidance').attr('disabled', false);
        }
      });
    });
  </script>