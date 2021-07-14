<div class="tab-pane active" id="data_registrasi">
  <?php $data = ['init' => true, 'set_active' => [1]];
  $this->load->view('manage_customer/leads_customer_data/wizard', $data); ?>
  <form id="form_registrasi" class='form-horizontal form_'>
    <div class="form-group">
      <label class="col-sm-2 control-label">Leads ID</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='leads_id' value='<?= $row->leads_id ?>' disabled>
        </div>
      </div>
      <label class="col-sm-2 control-label">Tanggal Registrasi</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datetimepicker" name='tanggalRegistrasi' value='<?= $row->tanggalRegistrasiEng ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Customer ID</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='customerId' value='<?= $row->customerId ?>' disabled>
        </div>
      </div>
      <label class="col-sm-2 control-label">Kategori Modul Leads</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="cmsSource" class='form-control' name='cmsSource' <?= $disabled ?>>
            <option value='<?= $row->cmsSource ?>'><?= $row->deskripsiCmsSource ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Deskripsi Event Virtual MD</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='deskripsiEvent' value='<?= $row->deskripsiEvent ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Tanggal Visit Booth</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datetimepicker" name='tanggalVisitBooth' value='<?= $row->tanggalVisitBoothEng ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Nama *</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" required id='nama' name='nama' value='<?= $row->nama ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Segmen Produk</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="segmenProduk" class='form-control' name='segmenProduk' <?= $disabled ?>>
            <option value=""></option>
            <?php $list = ['C' => 'Cub', 'M' => 'Matic', 'S' => 'Sport'];
            foreach ($list as $key => $val) { ?>
              <option value='<?= $key ?>' <?= $key == $row->segmenProduk ? 'selected' : '' ?>><?= $val ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">No. HP Pendaftaran *</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='noHP' required value='<?= $row->noHP ?>' <?= $disabled ?> onkeypress="only_number(event)">
        </div>
      </div>
      <label class="col-sm-2 control-label">Tgl. Download Brosur</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datetimepicker" name='tanggalDownloadBrosur' value='<?= $row->tanggalDownloadBrosurEng ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">No. Telepon</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='noTelp' value='<?= $row->noTelp ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Series Brosur</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='seriesBrosur' value='<?= $row->seriesBrosur ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Email</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='email' value='<?= $row->email ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Tanggal Wishlist</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datetimepicker" name='tanggalWishlist' value='<?= $row->tanggalWishlistEng ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Kota / Kabupaten</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="id_kabupaten_kota_from_other_db_2" class='form-control' name='kabupaten' <?= $disabled ?>>
            <option value='<?= $row->kabupaten ?>'><?= $row->deskripsiKabupatenKotaDomisili ?></option>
          </select>
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
          <input type="text" class="form-control" name='eventCodeInvitation' value='<?= $row->eventCodeInvitation ?>' <?= $disabled ?>>
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
<?php
$data['data'] = ['selectKabupatenKotaFromOtherDb2'];
$this->load->view('additionals/dropdown_wilayah', $data);

$data['data'] = ['selectCmsSource'];
$this->load->view('additionals/dropdown_search_menu_leads_customer_data', $data);

?>
<script>
  function saveRegistrasi() {
    var val_form_registrasi = new FormData($('#form_registrasi')[0]);
    val_form_registrasi.append('leads_id', '<?= $row->leads_id ?>');
    <?php if ($disabled == 'disabled') { ?>
      changeTabs('pengajuan_kontak_sales');
      return false;
    <?php } ?>
    $('#form_registrasi').validate({
      highlight: function(element, errorClass, validClass) {
        var elem = $(element);
        if (elem.hasClass("select2-hidden-accessible")) {
          $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
        } else {
          $(element).parents('.form-input').addClass('has-error');
        }
      },
      unhighlight: function(element, errorClass, validClass) {
        var elem = $(element);
        if (elem.hasClass("select2-hidden-accessible")) {
          $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
        } else {
          $(element).parents('.form-input').removeClass('has-error');
        }
      },
      errorPlacement: function(error, element) {
        var elem = $(element);
        if (elem.hasClass("select2-hidden-accessible")) {
          element = $("#select2-" + elem.attr("id") + "-container").parent();
          error.insertAfter(element);
        } else {
          error.insertAfter(element);
        }
      }
    })

    if ($('#form_registrasi').valid()) // check if form is valid
    {
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
    } else {
      Swal.fire({
        icon: 'error',
        title: '<font color="white">Peringatan</font>',
        html: '<font color="white">Silahkan lengkapi field yang wajib diisi</font>',
        background: '#dd4b39',
        confirmButtonColor: '#cc3422',
        confirmButtonText: 'Tutup',
        iconColor: 'white'
      })
    }
  }
</script>