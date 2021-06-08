<div class="tab-pane" id="pengajuan_kontak_sales">
  <?php $data = ['set_active' => [1, 2]];
  $this->load->view('manage_customer/leads_customer_data/wizard', $data); ?>
  <form id="form_pengajuan_kontak_sales" class='form-horizontal'>
    <div class="form-group">
      <label class="col-sm-2 control-label">Tanggal Pengajuan</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datetimepicker" name='tanggalPengajuan' required value='<?= $row->tanggalPengajuanEng ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Tanggal Kontak Sales</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datetimepicker" name='tanggalKontakSales' required value='<?= $row->tanggalKontakSalesEng ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Nama Pengajuan</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='namaPengajuan' required value='<?= $row->namaPengajuan ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-6 col-md-6">
        <div class="form-group">
          <label class="col-sm-4 control-label">No. HP Pengajuan</label>
          <div class="form-input">
            <div class="col-sm-8">
              <input type="text" class="form-control" name='noHpPengajuan' required value='<?= $row->noHpPengajuan ?>' <?= $disabled ?>>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Kota/Kabupaten Pengajuan</label>
          <div class="form-input">
            <div class="col-sm-8">
              <input type="text" class="form-control" name='kabupatenPengajuan' required value='<?= $row->kabupatenPengajuan ?>' <?= $disabled ?>>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Kode & Tipe Motor Diminati</label>
          <div class="form-input">
            <div class="col-sm-8">
              <select style='width:100%' id="id_tipe" class='form-control' name='kodeTypeUnit' <?= $disabled ?>>
                <option value='<?= $row->kodeTypeUnit ?>'><?= $row->concatKodeTypeUnit ?></option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Kode & Warna Motor Diminati</label>
          <div class="form-input">
            <div class="col-sm-8">
              <select style="width:100%" id="id_warna" class='form-control' name='kodeWarnaUnit' <?= $disabled ?>>
                <option value='<?= $row->kodeWarnaUnit ?>'><?= $row->concatKodeWarnaUnit ?></option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Minat Riding Test</label>
          <div class="form-input">
            <div class="col-sm-1">
              <input type="radio" name="minatRidingTest" value="1" class="flat-red" style="position: absolute; opacity: 0;" <?= $row->minatRidingTest == 1 ? 'checked' : '' ?> <?= $disabled ?>> Ya
            </div>
            <div class="col-sm-1">
              <input type="radio" name="minatRidingTest" value="0" class="flat-red" style="position: absolute; opacity: 0;" <?= $row->minatRidingTest == 0 ? 'checked' : '' ?> <?= $disabled ?>> Tidak
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-6">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">
              Tabel History Interaksi
            </h3>
            <div class="box-tools pull-right">
              <button type="button" id="showModalHistoryInteraksi" class="btn btn-primary btn-flat btn-xs">View All</button>
            </div>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <table class='table table-condensed table-bordered table-striped serverside-tables' style="width:100%">
                <thead>
                  <th>Kode Unit + Warna Motor</th>
                  <th>Tgl. Riding Test</th>
                  <th>Source Data</th>
                  <th>Keterangan</th>
                  <th>Customer Action Date</th>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group" style='padding-top:20px'>
      <div class="col-sm-6">
        <button type="button" id="backTo_data_registrasi" class="btn btn-primary btn-flat" onclick="saveDataPengajuanKontakSales('data_registrasi')"><i class="fa fa-backward"></i> Halaman Sebelumnya</button>
      </div>
      <div class="col-sm-6" align="right">
        <button type="button" id="nextTo_data_pendukung_probing_1" class="btn btn-primary btn-flat" onclick="saveDataPengajuanKontakSales('data_pendukung_probing_1')"><i class="fa fa-forward"></i> Halaman Berikutnya</button>
      </div>
    </div>
  </form>
</div>
<?php $data['data'] = ['selectTipe', 'selectWarna'];
$this->load->view('additionals/dropdown_series_tipe', $data); ?>
<script>
  function saveDataPengajuanKontakSales(tabs) {
    console.log(tabs);
    if (tabs == 'data_registrasi') {
      var set_id = "#backTo_data_registrasi";
      var default_name_button = '<i class = "fa fa-backward"></i> Halaman Sebelumnya';
    } else if (tabs == 'pendukung_probing_1') {
      var set_id = "#nextTo_data_pendukung_probing_1";
      var default_name_button = '<i class = "fa fa-forward"></i> Halaman Berikutnya';
    }
    var val_form_pengajuan_kontak_sales = new FormData($('#form_pengajuan_kontak_sales')[0]);
    val_form_pengajuan_kontak_sales.append('leads_id', '<?= $row->leads_id ?>');
    <?php if ($disabled == 'disabled') { ?>
      changeTabs(tabs);
      return false;
    <?php } ?>
    $.ajax({
      beforeSend: function() {
        $(set_id).html('<i class="fa fa-spinner fa-spin"></i> Process');
        $(set_id).attr('disabled', true);
      },
      enctype: 'multipart/form-data',
      url: '<?= site_url(get_controller() . '/saveEditPengajuanKontakSales') ?>',
      type: "POST",
      data: val_form_pengajuan_kontak_sales,
      processData: false,
      contentType: false,
      // cache: false,
      dataType: 'JSON',
      success: function(response) {
        if (response.status == 1) {
          changeTabs(tabs);
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
        $(set_id).attr('disabled', false);
        $(set_id).html(default_name_button);
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
        $(set_id).html(default_name_button);
        $(set_id).attr('disabled', false);
      }
    });
  }
</script>