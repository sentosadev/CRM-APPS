<div class="tab-pane" id="data_pendukung_probing_1">
  <?php $data = ['set_active' => [1, 2, 3]];
  $this->load->view('manage_customer/leads_customer_data/wizard', $data); ?>
  <form id="form_data_pendukung_probing_1" class='form-horizontal form_'>
    <div class="form-group">
      <label class="col-sm-2 control-label">Platform Data</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="id_platform_data" class='form-control' name='platformData' <?= $disabled ?> <?= $row->platform_for != 'MD' ? 'disabled' : '' ?>>
            <option value='<?= $row->platformData ?>'><?= $row->deskripsiPlatformData ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Source Data</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="id_source_leads" class='form-control' name='sourceData' <?= $disabled ?> <?= $row->platform_for != 'MD' ? 'disabled' : '' ?>>
            <option value='<?= $row->sourceData ?>'><?= $row->deskripsiSourceData ?></option>
          </select>
        </div>
      </div>
      <!-- <label class="col-sm-2 control-label">Keterangan Preferensi Dealer Lain</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='keteranganPreferensiDealerLain' value='<?= $row->keteranganPreferensiDealerLain ?>' <?= $disabled ?>>
        </div>
      </div> -->
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Kategori Konsumen</label>
      <div class="form-input">
        <div class="col-sm-1">
          <input type="radio" name="customerType" value="V" class="flat-red" style="position: absolute; opacity: 0;" <?= $row->customerType == 'V' ? 'checked' : '' ?> <?= $disabled ?>> VIP
        </div>
        <div class="col-sm-3">
          <input type="radio" name="customerType" value="R" class="flat-red" style="position: absolute; opacity: 0;" <?= $row->customerType == 'R' ? 'checked' : '' ?> <?= $disabled ?>> Reguler
        </div>
      </div>
      <label class="col-sm-2 control-label">Alasan Pindah Dealer</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='alasanPindahDealer' value='<?= $row->alasanPindahDealer ?>' disabled>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Nama Dealer Pembelian Sebelumnya</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="kodeDealerPembelianSebelumnya" class='form-control' name='kodeDealerPembelianSebelumnya' <?= $disabled ?>>
            <option value='<?= $row->kodeDealerPembelianSebelumnya ?>'><?= $row->namaDealerPembelianSebelumnya ?></option>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">Gender</label>
      <div class="form-input">
        <div class="col-sm-1">
          <input type="radio" name="gender" value="1" class="flat-red" style="position: absolute; opacity: 0;" <?= $row->gender == '1' ? 'checked' : '' ?> <?= $disabled ?>> Pria
        </div>
        <div class="col-sm-3">
          <input type="radio" name="gender" value="0" class="flat-red" style="position: absolute; opacity: 0;" <?= $row->gender == '0' ? 'checked' : '' ?> <?= $disabled ?>> Wanita
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Nama Leasing Pembelian Sebelumnya</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="kodeLeasingPembelianSebelumnya" name="kodeLeasingPembelianSebelumnya" class='form-control' name='kodeLeasingPembelianSebelumnya' <?= $disabled ?>>
            <option value='<?= $row->kodeLeasingPembelianSebelumnya ?>'><?= $row->namaLeasingPembelianSebelumnya ?></option>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">No. KTP</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='noKtp' value='<?= $row->noKtp ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Frame No. Sebelumnya</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='noFramePembelianSebelumnya' value='<?= $row->noFramePembelianSebelumnya ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Tanggal Pembelian Terakhir</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datepicker" name='tanggalPembelianTerakhir' value='<?= $row->tanggalPembelianTerakhir ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Deskripsi Pekerjaan (KTP)</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="kodePekerjaan" class='form-control' name='kodePekerjaanKtp' <?= $disabled ?>>
            <option value='<?= $row->kodePekerjaanKtp ?>'><?= $row->deskripsiPekerjaanKtp ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Desk. Tipe Unit Pembelian Terakhir</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='deskripsiTipeUnitPembelianTerakhir' value='<?= $row->deskripsiTipeUnitPembelianTerakhir ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Deskripsi Pekerjaan (Saat Ini)</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="kodeSubPekerjaan" class='form-control' name='kodePekerjaan' <?= $disabled ?>>
            <option value='<?= $row->kodePekerjaan ?>'><?= $row->deskripsiPekerjaan ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <!-- <label class="col-sm-2 control-label">Kategori Preferensi Dealer</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='kategoriPreferensiDealer' value='<?= $row->kategoriPreferensiDealer ?>' <?= $disabled ?>>
        </div>
      </div> -->
      <label class="col-sm-offset-6 col-sm-2 control-label">Promo Yang Diminati Customer</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" id='promoYangDiminatiCustomer' name='promoYangDiminatiCustomer' value='<?= $row->promoYangDiminatiCustomer ?>' <?= $disabled ?>>

        </div>
      </div>
    </div>
    <div class="form-group">
      <!-- <label class="col-sm-2 control-label">Nama Dealer Preferensi Customer</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='namaDealerPreferensiCustomer' value='<?= $row->namaDealerPreferensiCustomer ?>' <?= $disabled ?>>
        </div>
      </div> -->
      <label class="col-sm-offset-6 col-sm-2 control-label">Pendidikan</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="idPendidikan" class='form-control' name='idPendidikan' <?= $disabled ?>>
            <option value='<?= $row->idPendidikan ?>'><?= $row->deskripsiPendidikan ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-6 col-md-6">
        <label class="col-sm-4 control-label">Alamat</label>
        <div class="form-input">
          <div class="col-sm-8">
            <textarea class='form-control' rows=5 name='alamat'><?= $row->alamat ?></textarea>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-6">
        <div class="form-group">

          <label class="col-sm-4 control-label">Agama</label>
          <div class="form-input">
            <div class="col-sm-8">
              <select style='width:100%' id="idAgama" class='form-control' name='idAgama' <?= $disabled ?>>
                <option value='<?= $row->idAgama ?>'><?= $row->deskripsiAgama ?></option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Pengeluaran</label>
          <div class="form-input">
            <div class="col-sm-8">
              <select style='width:100%' id="pengeluaran" class='form-control' name='pengeluaran' <?= $disabled ?>>
                <option value='<?= $row->pengeluaran ?>'><?= $row->deskripsiPengeluaran ?></option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Preferensi Promo Yang Diminati Customer</label>
          <div class="form-input">
            <div class="col-sm-8">
              <input type="text" class="form-control" name='preferensiPromoDiminatiCustomer' value='<?= $row->preferensiPromoDiminatiCustomer ?>' <?= $disabled ?>>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Provinsi</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="id_provinsi_from_other_db" class='form-control' name='provinsi' <?= $disabled ?>>
            <option value='<?= $row->provinsi ?>'><?= $row->deskripsiProvinsiDomisili ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Kabupaten / Kota Domisili</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="id_kabupaten_kota_from_other_db" class='form-control' name='kabupaten' <?= $disabled ?>>
            <option value='<?= $row->kabupaten ?>'><?= $row->deskripsiKabupatenKotaDomisili ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Kecamatan Domisili</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="id_kecamatan_from_other_db" class='form-control' name='kecamatan' <?= $disabled ?>>
            <option value='<?= $row->kecamatan ?>'><?= $row->deskripsiKecamatanDomisili ?></option>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">Tanggal Rencana Pembelian</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datepicker" name='tanggalRencanaPembelian' value='<?= $row->tanggalRencanaPembelian ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Kelurahan Domisili</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="id_kelurahan_from_other_db" class='form-control' name='kelurahan' <?= $disabled ?>>
            <option value='<?= $row->kelurahan ?>'><?= $row->deskripsiKelurahanDomisili ?></option>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">Kategori Prospect</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='kategoriProspect' value='<?= $row->kategoriProspect ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Kecamatan Kantor</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="id_kecamatan2" class='form-control' name='idKecamatanKantor' <?= $disabled ?>>
            <option value='<?= $row->idKecamatanKantor ?>'><?= $row->deskripsiKecamatanKantor ?></option>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">Nama Community</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='namaCommunity' value='<?= $row->namaCommunity ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Rencana Pembayaran</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="rencanaPembayaran" class='form-control' name='rencanaPembayaran' <?= $disabled ?>>
            <option value=""></option>
            <?php $list = ['cash' => 'Cash', 'kredit' => 'Kredit'];
            foreach ($list as $key => $val) { ?>
              <option value='<?= $key ?>' <?= $key == $row->rencanaPembayaran ? 'selected' : '' ?>><?= $val ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group" style='padding-top:20px'>
      <div class="col-sm-6">
        <button type="button" id="backTo_pengajuan_kontak_sales" class="btn btn-primary btn-flat" onclick="saveDataPendukung('pengajuan_kontak_sales')"><i class="fa fa-backward"></i> Halaman Sebelumnya</button>
      </div>
      <div class="col-sm-6" align="right">
        <button onclick="saveDataPendukung('data_follow_up_1')" type="button" id="nextTo_data_follow_up_1" class="btn btn-primary btn-flat"><i class="fa fa-forward"></i> Halaman Berikutnya</button>
      </div>
    </div>
  </form>
</div>
<?php

$data['data'] = ['selectPekerjaan', 'selectPendidikan', 'selectAgama', 'selectDealerPembelianSebelumnya', 'selectLeasingPembelianSebelumnya', 'selectJenisMotorYangDimilikiSekarang', 'selectMerkMotorYangDimilikiSekarang', 'selectSumberProspek', 'selectSubPekerjaan', 'selectPlatformData', 'selectSourceLeads', 'filterSourceLeadsByPlatformData', 'selectPengeluaran', 'filterPlatformDataIn'];
$this->load->view('additionals/dropdown_search_menu_leads_customer_data', $data);

$data['data'] = ['selectProvinsiFromOtherDb', 'selectKabupatenKotaFromOtherDb', 'selectKecamatanFromOtherDb', 'selectKecamatan2', 'selectKelurahanFromOtherDb'];
$this->load->view('additionals/dropdown_wilayah', $data);

?>
<script>
  function saveDataPendukung(tabs) {
    if (tabs == 'pengajuan_kontak_sales') {
      var set_id = "#backTo_pengajuan_kontak_sales";
      var default_name_button = '<i class = "fa fa-backward"></i> Halaman Sebelumnya';
    } else if (tabs == 'data_follow_up_1') {
      var set_id = "#nextTo_data_follow_up_1";
      var default_name_button = '<i class = "fa fa-forward"></i> Halaman Berikutnya';
    }
    var val_form_data_pendukung_probing_1 = new FormData($('#form_data_pendukung_probing_1')[0]);
    val_form_data_pendukung_probing_1.append('leads_id', '<?= $row->leads_id ?>');

    <?php if ($disabled == 'disabled') { ?>
      changeTabs(tabs);
      return false;
    <?php } ?>

    $('#form_data_pendukung_probing_1').validate({
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
    if ($('#form_data_pendukung_probing_1').valid()) // check if form is valid
    {
      $.ajax({
        beforeSend: function() {
          $(set_id).html('<i class="fa fa-spinner fa-spin"></i> Process');
          $(set_id).attr('disabled', true);
        },
        enctype: 'multipart/form-data',
        url: '<?= site_url(get_controller() . '/saveEditPendukungProbing_1') ?>',
        type: "POST",
        data: val_form_data_pendukung_probing_1,
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