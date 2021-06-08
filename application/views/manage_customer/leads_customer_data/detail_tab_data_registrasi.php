<div class="tab-pane active" id="data_registrasi">
  <?php $data = ['init' => true, 'set_active' => [1]];
  $this->load->view('manage_customer/leads_customer_data/wizard', $data); ?>
  <form id="form_registrasi" class='form-horizontal'>
    <div class="form-group">
      <label class="col-sm-2 control-label">Leads ID</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='leads_id' required value='<?= $row->leads_id ?>' disabled>
        </div>
      </div>
      <label class="col-sm-2 control-label">Tanggal Registrasi</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datetimepicker" name='tanggalRegistrasi' required value='<?= $row->tanggalRegistrasiEng ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Customer ID</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='customerId' required value='<?= $row->customerId ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Kategori Modul Leads</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='kategoriModulLeads' required value='<?= $row->kategoriModulLeads ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Deskripsi Event Virtual MD</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='deskripsiEvent' required value='<?= $row->deskripsiEvent ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Tanggal Visit Booth</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datetimepicker" name='tanggalVisitBooth' required value='<?= $row->tanggalVisitBoothEng ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Nama</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" id='nama' name='nama' required value='<?= $row->nama ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Segmen Produk</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='segmenProduk' required value='<?= $row->segmenProduk ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">No. HP Pendaftaran</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='noHP' required value='<?= $row->noHP ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Tgl. Download Brosur</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datetimepicker" name='tanggalDownloadBrosur' required value='<?= $row->tanggalDownloadBrosurEng ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">No. Telepon</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='noTelp' required value='<?= $row->noTelp ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Series Brosur</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='seriesBrosur' required value='<?= $row->seriesBrosur ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Email</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='email' required value='<?= $row->email ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Tanggal Wishlist</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datetimepicker" name='tanggalWishlist' required value='<?= $row->tanggalWishlistEng ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Kota / Kabupaten</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='kabupaten' required value='<?= $row->kabupaten ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Series Wishlist</label>
      <div class="form-input">
        <div class="col-sm-4">
          <textarea class='form-control' name='seriesWishlist' <?= $disabled ?>><?= $row->seriesWishlist ?></textarea>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Event Code Invitation</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='eventCodeInvitation' required value='<?= $row->eventCodeInvitation ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Status No. Handphone</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="statusNoHp" class='form-control' name='statusNoHp' <?= $disabled ?>>
            <option value=""></option>
            <?php $list = ['1' => 'Pra Bayar(Isi Ulang)', '2' => 'Pasca Bayar/Billing/Tagihan'];
            foreach ($list as $key => $val) { ?>
              <option value='<?= $key ?>' <?= $key == $row->statusNoHp ? 'selected' : '' ?>><?= $val ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
  </form>
  <div class="form-group">
    <div class="col-sm-12" align="right">
      <button type="button" id="nextTo_pengajuan_kontak_sales" class="btn btn-primary btn-flat" onclick="saveRegistrasi()"><i class="fa fa-forward"></i> Halaman Berikutnya</button>
    </div>
  </div>
</div>

<script>
  function saveRegistrasi() {
    var val_form_registrasi = new FormData($('#form_registrasi')[0]);
    val_form_registrasi.append('leads_id', '<?= $row->leads_id ?>');
    <?php if ($disabled == 'disabled') { ?>
      changeTabs('pengajuan_kontak_sales');
      return false;
    <?php } ?>
    $.ajax({
      beforeSend: function() {
        $('#nextTo_pengajuan_kontak_sales').html('<i class="fa fa-spinner fa-spin"></i> Process');
        $('#nextTo_pengajuan_kontak_sales').attr('disabled', true);
      },
      enctype: 'multipart/form-data',
      url: '<?= site_url(get_controller() . '/saveEditRegistrasi') ?>',
      type: "POST",
      data: val_form_registrasi,
      processData: false,
      contentType: false,
      // cache: false,
      dataType: 'JSON',
      success: function(response) {
        if (response.status == 1) {
          changeTabs('pengajuan_kontak_sales');
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
        $('#nextTo_pengajuan_kontak_sales').attr('disabled', false);
        $('#nextTo_pengajuan_kontak_sales').html('<i class="fa fa-forward"></i> Halaman Berikutnya');
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
        $('#nextTo_pengajuan_kontak_sales').html('<i class="fa fa-forward"></i> Halaman Berikutnya');
        $('#nextTo_pengajuan_kontak_sales').attr('disabled', false);
      }
    });
  }
</script>