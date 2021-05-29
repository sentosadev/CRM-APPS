  <?php $disabled = '';
  $dt = ['disabled' => $disabled, 'row' => $row];
  ?>
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
              <li class=""><a href="#pengajuan_kontak_sales" aria-expanded="false">Pengajuan & Kontak Sales</a></li>
              <li class=""><a href="#" aria-expanded="false">Data Pendukung & Probing 1</a></li>
              <li class=""><a href="#" aria-expanded="false">Data Pendukung & Probing 2</a></li>
              <li class=""><a href="#" aria-expanded="false">Data Pendukung & Probing 3</a></li>
            </ul>
            <div class="tab-content">
              <?php $this->load->view('manage_customer/leads_customer_data/edit_tab_data_registrasi', $dt); ?>
              <?php
              $this->load->view('manage_customer/leads_customer_data/edit_tab_pengajuan_kontak_sales', $dt); ?>
            </div>
            <!-- /.tab-content -->
          </div>
        </div>
      </div>
      <!-- /.box-body -->

    </div>
    <!-- /.box -->
  </section>
  <script>
    function changeTabs(tabs) {
      $('[href="#' + tabs + '"]').tab('show');
      document.body.scrollTop = 0; // For Safari
      document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
  </script>