<div class="tab-pane" id="data_pendukung_probing_1">
  <?php $data = ['set_active' => [1, 2, 3]];
  $this->load->view('manage_customer/leads_customer_data/wizard', $data); ?>
  <form id="form_data_pendukung_probing_1" class='form-horizontal'>
    <div class="form-group">
      <label class="col-sm-2 control-label">Platform Data</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="id_platform_data" class='form-control' name='platformData' <?= $disabled ?>>
            <option value='<?= $row->platformData ?>'><?= $row->deskripsiPlatformData ?></option>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">Provinsi</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="id_provinsi" class='form-control' name='provinsi' <?= $disabled ?>>
            <option value='<?= $row->provinsi ?>'><?= $row->deskripsiProvinsi ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Source Data</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="id_source_leads" class='form-control' name='sourceData' <?= $disabled ?>>
            <option value='<?= $row->sourceData ?>'><?= $row->deskripsiSourceData ?></option>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">Keterangan Preferensi Dealer Lain</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='keteranganPreferensiDealerLain' required value='<?= $row->keteranganPreferensiDealerLain ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Kategori Konsumen</label>
      <div class="form-input">
        <div class="col-sm-1">
          <input type="radio" name="kategoriKonsumen" value="vip" class="flat-red" style="position: absolute; opacity: 0;" <?= $row->kategoriKonsumen == 'vip' ? 'checked' : '' ?> <?= $disabled ?>> VIP
        </div>
        <div class="col-sm-3">
          <input type="radio" name="kategoriKonsumen" value="reguler" class="flat-red" style="position: absolute; opacity: 0;" <?= $row->kategoriKonsumen == 'reguler' ? 'checked' : '' ?> <?= $disabled ?>> Reguler
        </div>
      </div>
      <label class="col-sm-2 control-label">Alasan Pindah Dealer</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='alasanPindahDealer' required value='<?= $row->alasanPindahDealer ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Nama Dealer Sebelumnya</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="kodeDealerSebelumnya" class='form-control' name='kodeDealerSebelumnya' <?= $disabled ?>>
            <option value='<?= $row->kodeDealerSebelumnya ?>'><?= $row->namaDealerSebelumnya ?></option>
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
      <label class="col-sm-2 control-label">Nama Leasing Sebelumnya</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="kodeLeasingSebelumnya" name="kodeLeasingSebelumnya" class='form-control' name='kodeLeasingSebelumnya' <?= $disabled ?>>
            <option value='<?= $row->kodeLeasingSebelumnya ?>'><?= $row->namaLeasingSebelumnya ?></option>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">No. KTP</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='noKtp' required value='<?= $row->noKtp ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Tanggal Pembelian Terakhir</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datepicker" name='tanggalPembelianTerakhir' required value='<?= $row->tanggalPembelianTerakhir ?>' <?= $disabled ?>>

        </div>
      </div>
      <label class="col-sm-2 control-label">Deskripsi Pekerjaan (Saat Ini)</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="kodePekerjaan" class='form-control' name='kodePekerjaan' <?= $disabled ?>>
            <option value='<?= $row->kodePekerjaan ?>'><?= $row->deskripsiPekerjaan ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Desk. Tipe Unit Pembelian Terakhir</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='deskripsiTipeUnitPembelianTerakhir' required value='<?= $row->deskripsiTipeUnitPembelianTerakhir ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Promo Yang Diminati Customer</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" id='promoYangDiminatiCustomer' name='promoYangDiminatiCustomer' required value='<?= $row->promoYangDiminatiCustomer ?>' <?= $disabled ?>>

        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Kategori Preferensi Dealer</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='kategoriPreferensiDealer' required value='<?= $row->kategoriPreferensiDealer ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Pendidikan</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="idPendidikan" class='form-control' name='idPendidikan' <?= $disabled ?>>
            <option value='<?= $row->idPendidikan ?>'><?= $row->deskripsiPendidikan ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Nama Dealer Preferensi Customer</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='namaDealerPreferensiCustomer' required value='<?= $row->namaDealerPreferensiCustomer ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Agama</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="idAgama" class='form-control' name='idAgama' <?= $disabled ?>>
            <option value='<?= $row->idAgama ?>'><?= $row->deskripsiAgama ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Kecamatan Domisili</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="id_kecamatan" class='form-control' name='kecamatan' <?= $disabled ?>>
            <option value='<?= $row->kecamatan ?>'><?= $row->deskripsiKecamatanDomisili ?></option>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">Tanggal Rencana Pembelian</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control datepicker" name='tanggalRencanaPembelian' required value='<?= $row->tanggalRencanaPembelian ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Kelurahan</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="id_kelurahan" class='form-control' name='kelurahan' <?= $disabled ?>>
            <option value='<?= $row->kelurahan ?>'><?= $row->deskripsiKelurahanDomisili ?></option>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">Kategori Prospect</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="text" class="form-control" name='kategoriProspect' required value='<?= $row->kategoriProspect ?>' <?= $disabled ?>>
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
          <input type="text" class="form-control" name='namaCommunity' required value='<?= $row->namaCommunity ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Jenis Motor yg dimiliki sekarang</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="idJenisMotorYangDimilikiSekarang" class='form-control' name='idJenisMotorYangDimilikiSekarang' <?= $disabled ?>>
            <option value='<?= $row->idJenisMotorYangDimilikiSekarang ?>'><?= $row->jenisMotorYangDimilikiSekarang ?></option>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">Merk Motor yg dimiliki sekarang</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="idMerkMotorYangDimilikiSekarang" class='form-control' name='idMerkMotorYangDimilikiSekarang' <?= $disabled ?>>
            <option value='<?= $row->idMerkMotorYangDimilikiSekarang ?>'><?= $row->merkMotorYangDimilikiSekarang ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Yang Menggunakan Sepeda Motor</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="yangMenggunakanSepedaMotor" class='form-control' name='yangMenggunakanSepedaMotor' <?= $disabled ?>>
            <option></option>
            <?php $list = ['Saya Sendiri', 'Anak', 'Pasangan Suami/Istri'];
            foreach ($list as $val) { ?>
              <option value='<?= $val ?>' <?= $val == $row->yangMenggunakanSepedaMotor ? 'selected' : '' ?>><?= $val ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">Status Prospek</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="statusProspek" class='form-control' name='statusProspek' <?= $disabled ?>>
            <option></option>
            <?php $list = ['Low', 'Hot', 'Not Deal', 'Deal'];
            foreach ($list as $val) { ?>
              <option value='<?= $val ?>' <?= $val == $row->statusProspek ? 'selected' : '' ?>><?= $val ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Longitude</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="number" class="form-control" name='longitude' required value='<?= $row->longitude ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">Latitude</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="number" class="form-control" id='latitude' name='latitude' required value='<?= $row->latitude ?>' <?= $disabled ?>>

        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">No. KK</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="number" class="form-control" name='noKK' required value='<?= $row->noKK ?>' <?= $disabled ?>>
        </div>
      </div>
      <label class="col-sm-2 control-label">No. NPWP</label>
      <div class="form-input">
        <div class="col-sm-4">
          <input type="number" class="form-control" id='npwp' name='npwp' required value='<?= $row->npwp ?>' <?= $disabled ?>>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Jenis Customer</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="jenisCustomer" class='form-control' name='jenisCustomer' <?= $disabled ?>>
            <option value=""></option>
            <?php $list = ['regular' => 'Regular', 'group_customer' => 'Group Customer'];
            foreach ($list as $key => $val) { ?>
              <option value='<?= $key ?>' <?= $key == $row->jenisCustomer ? 'selected' : '' ?>><?= $val ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">Sumber Prospek</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="idSumberProspek" class='form-control' name='idSumberProspek' <?= $disabled ?>>
            <option value='<?= $row->idSumberProspek ?>'><?= $row->sumberProspek ?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Jenis Kewarganegaraan</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="jenisKewarganegaraan" class='form-control' name='jenisKewarganegaraan' <?= $disabled ?>>
            <option value=""></option>
            <?php $list = ['WNA' => 'WNA', 'WNI' => 'WNI'];
            foreach ($list as $key => $val) { ?>
              <option value='<?= $key ?>' <?= $key == $row->jenisKewarganegaraan ? 'selected' : '' ?>><?= $val ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
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
    <div class="form-group">
      <label class="col-sm-2 control-label">Deskripsi Pekerjaan (KTP)</label>
      <div class="form-input">
        <div class="col-sm-4">
          <select style='width:100%' id="kodePekerjaan2" class='form-control' name='kodePekerjaanKtp' <?= $disabled ?>>
            <option value='<?= $row->kodePekerjaanKtp ?>'><?= $row->deskripsiPekerjaanKtp ?></option>
          </select>
        </div>
      </div>
      <label class="col-sm-2 control-label">Prioritas Prospect Customer</label>
      <div class="form-input">
        <div class="col-sm-1">
          <input type="radio" name="prioritasProspekCustomer" value="1" class="flat-red" style="position: absolute; opacity: 0;" <?= $row->prioritasProspekCustomer == '1' ? 'checked' : '' ?> <?= $disabled ?>> Ya
        </div>
        <div class="col-sm-3">
          <input type="radio" name="prioritasProspekCustomer" value="0" class="flat-red" style="position: absolute; opacity: 0;" <?= $row->prioritasProspekCustomer == '0' ? 'checked' : '' ?> <?= $disabled ?>> Tidak
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
<?php $data['data'] = ['selectPekerjaan', 'selectPendidikan', 'selectAgama', 'selectDealerSebelumnya', 'selectLeasingSebelumnya', 'selectJenisMotorYangDimilikiSekarang', 'selectMerkMotorYangDimilikiSekarang', 'selectSumberProspek'];
$this->load->view('additionals/dropdown_search_menu_leads_customer_data', $data); ?>
<?php $data['data'] = ['selectProvinsi', 'selectKecamatan', 'selectKecamatan2', 'selectKelurahan'];
$this->load->view('additionals/dropdown_wilayah', $data); ?>
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
  }
</script>