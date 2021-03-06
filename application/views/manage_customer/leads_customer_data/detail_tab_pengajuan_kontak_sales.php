<div class="tab-pane" id="pengajuan_kontak_sales">
  <?php $data = ['set_active' => [1, 2]];
  $this->load->view('manage_customer/leads_customer_data/wizard', $data); ?>
  <form id="form_pengajuan_kontak_sales" class='form-horizontal form_'>
    <div class="form-group">
      <label class="col-sm-2 control-label">Tanggal Pengajuan</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datetimepicker" name='tanggalPengajuan' value='<?= $row->tanggalPengajuanEng ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Tanggal Kontak Sales</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datetimepicker" name='tanggalKontakSales' value='<?= $row->tanggalKontakSalesEng ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Nama Pengajuan (Salesman)</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style="width:100%" id="id_karyawan_dealer" class='form-control' name='id_karyawan_dealer' <?= $disabled ?>>
            <option value='<?= $row->id_karyawan_dealer ?>'><?= $row->namaPengajuan ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-6 col-md-6">
        <div class="form-group">
          <label class="col-sm-4 control-label">No. HP Pengajuan</label>
          <div class="form-input">
            <div class="col-sm-8">
              <input type="text" class="form-control" name='noHpPengajuan' value='<?= $row->noHpPengajuan ?>' <?= $disabled ?> onkeypress="only_number(event)">
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Provinsi Pengajuan</label>
          <div class="form-input">
            <div class="col-sm-8">
              <select id="idProvinsiPengajuan" class='form-control' name='idProvinsiPengajuan' style='width:100%'>
                <option value='<?= $row->idProvinsiPengajuan ?>'><?= $row->provinsiPengajuan ?></option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Kota/Kabupaten Pengajuan</label>
          <div class="form-input">
            <div class="col-sm-8">
              <select id="idKabupatenPengajuan" class='form-control' name='idKabupatenPengajuan' style='width:100%'>
                <option value='<?= $row->idKabupatenPengajuan ?>'><?= $row->kabupatenPengajuan ?></option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Minat Riding Test</label>
          <div class="form-input">
            <div class="col-sm-1">
              <input type="radio" name="minatRidingTest" value="1" class="flat-red minatRidingTest" style="position: absolute; opacity: 0;" <?= $row->minatRidingTest == 1 ? 'checked' : '' ?> <?= $disabled ?>> Ya
            </div>
            <div class="col-sm-1">
              <input type="radio" name="minatRidingTest" value="0" class="flat-red minatRidingTest" style="position: absolute; opacity: 0;" <?= $row->minatRidingTest == 0 ? 'checked' : '' ?> <?= $disabled ?>> Tidak
            </div>
          </div>
        </div>
        <div class="form-group" id="input_jadwalRidingTest" style="display:none">
          <label class="col-sm-4 control-label">Jadwal Riding Test</label>
          <div class="form-input">
            <div class="col-sm-8">
              <input type="text" class="form-control datetimepicker" name='jadwalRidingTest' value='<?= $row->jadwalRidingTestEng ?>' <?= $disabled ?> onkeypress="only_number(event)">
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-6">
      </div>
    </div>
    <div class="form-group" style='padding-top:20px'>
      <div class="col-sm-6">
        <button type="button" class="btn btn-primary btn-flat" onclick="saveDataPengajuanKontakSales(this, 'data_registrasi')"><i class="fa fa-backward"></i> Halaman Sebelumnya</button>
      </div>
      <div class="col-sm-6" align="right">
        <button type="button" class="btn btn-primary btn-flat" onclick="saveDataPengajuanKontakSales(this, 'data_pendukung_probing_1')"><i class="fa fa-forward"></i> Halaman Berikutnya</button>
      </div>
    </div>
  </form>
</div>
<?php
$data['data'] = ['selectTipeFromOtherDb', 'selectWarnaFromOtherDb', 'filterWarnaByTipe'];
$this->load->view('additionals/dropdown_series_tipe', $data);

$data['data'] = ['selectSalesmanFromOtherDb', 'selectKabupatenKotaPengajuan', 'selectProvinsiPengajuan'];
$this->load->view('additionals/dropdown_search_menu_leads_customer_data', $data);

$this->load->view(get_controller() . '/modal_history_interaksi');
?>
<script>
  function saveDataPengajuanKontakSales(el, tabs) {
    if (tabs == 'data_registrasi') {
      var default_name_button = '<i class = "fa fa-backward"></i> Halaman Sebelumnya';
    } else {
      var default_name_button = '<i class = "fa fa-forward"></i> Halaman Berikutnya';
    }
    var val_form_pengajuan_kontak_sales = new FormData($('#form_pengajuan_kontak_sales')[0]);
    val_form_pengajuan_kontak_sales.append('leads_id', '<?= $row->leads_id ?>');
    <?php if ($disabled == 'disabled') { ?>
      changeTabs(tabs);
      return false;
    <?php } ?>

    $('#form_pengajuan_kontak_sales').validate({
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
    if ($('#form_pengajuan_kontak_sales').valid()) // check if form is valid
    {
      $.ajax({
        beforeSend: function() {
          $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
          $(el).attr('disabled', true);
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
          $(el).attr('disabled', false);
          $(el).html(default_name_button);
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
          $(el).html(default_name_button);
          $(el).attr('disabled', false);
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

  function cekMinatRidingTest() {
    $('#input_jadwalRidingTest').hide()
    $("input[name = 'minatRidingTest']").each(function() {
      if (this.checked == true && this.value == 1) {
        $('#input_jadwalRidingTest').show()
      }
    })
  }
  $(document).ready(function() {
    cekMinatRidingTest()
  })

  $('.minatRidingTest').on('ifChanged', function() {
    cekMinatRidingTest()
  })
</script>