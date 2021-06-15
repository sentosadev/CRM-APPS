<?php
$total_fol_up = count($list_follow_up) == 0 ? 1 : count($list_follow_up);
$max_pertab = 2;
$fol_up_sekarang = 1;
for ($i = 1; $i <= $tot_tab_fol; $i++) {
  if ($i == 1) {
    $back = 'data_pendukung_probing_1';
  } else {
    $to = $i - 1;
    $back = "data_follow_up_$to";
  }
?>
  <div class="tab-pane" id="data_follow_up_<?= $i ?>">
    <?php $data = ['set_active' => [1, 2, 3]];
    $this->load->view('manage_customer/leads_customer_data/wizard', $data); ?>
    <label data-toggle="tooltip" data-html="true" data-html="true" class='tooltipInformasiCustomer'><i>Informasi</i> <i class='fa fa-info-circle'></i></label>
    <form id="form_data_follow_up_<?= $i ?>" class='form-horizontal form_'>
      <div class="row">
        <?php for ($x = 1; $x <= $max_pertab; $x++) {
          if (!isset($list_follow_up[$fol_up_sekarang])) continue;
        ?>
          <div class="col-sm-6">
            <div class="form-group">
              <label class="col-sm-4 control-label">PIC *</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control" name='pic_<?= $fol_up_sekarang ?>' required value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['pic'] : '' ?>' <?= $disabled ?>>
                  <input type="hidden" class="form-control" name='folup[]' required value='<?= $fol_up_sekarang ?>'>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">Tanggal Follow Up <?= $fol_up_sekarang ?></label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control datetimepicker" name='tglFollowUp_<?= $fol_up_sekarang ?>' required value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['tglFollowUp'] : '' ?>' <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">Keterangan Follow Up <?= $fol_up_sekarang ?></label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control" name='keteranganFollowUp_<?= $fol_up_sekarang ?>' required value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['keteranganFollowUp'] : '' ?>' <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">Media Komunikasi Fol. Up <?= $fol_up_sekarang ?></label>
              <div class="form-input">
                <div class="col-sm-8">
                  <select style="width:100%" id="id_media_kontak_fu_<?= $fol_up_sekarang ?>" class='form-control' name='id_media_kontak_fu_<?= $fol_up_sekarang ?>' <?= $disabled ?>>
                    <?php if (isset($list_follow_up[$fol_up_sekarang])) {
                      $lfu = $list_follow_up[$fol_up_sekarang]; ?>
                      <option value='<?= $lfu['id_media_kontak_fu'] ?>'><?= $lfu['media_kontak_fu'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">Tgl. Next Follow Up</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control datepicker" name='tglNextFollowUp_<?= $fol_up_sekarang ?>' required value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['tglNextFollowUp'] : '' ?>' <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">Keterangan Next Action Follow Up</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control" name='keteranganNextFollowUp_<?= $fol_up_sekarang ?>' required value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['keteranganNextFollowUp'] : '' ?>' <?= $disabled ?>>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">Status Komunikasi Fol. Up <?= $fol_up_sekarang ?></label>
              <div class="form-input">
                <div class="col-sm-8">
                  <select style="width:100%" id="id_status_fu_<?= $fol_up_sekarang ?>" class='form-control' name='id_status_fu_<?= $fol_up_sekarang ?>' <?= $disabled ?>>
                    <?php if (isset($list_follow_up[$fol_up_sekarang])) {
                      $lfu = $list_follow_up[$fol_up_sekarang]; ?>
                      <option value='<?= $lfu['id_status_fu'] ?>'><?= $lfu['status_fu'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <script>
              var $eventSelect<?= $fol_up_sekarang ?> = $("#id_status_fu_<?= $fol_up_sekarang ?>");

              $eventSelect<?= $fol_up_sekarang ?>.on("change", function(e) {
                data = $eventSelect<?= $fol_up_sekarang ?>.select2('data')[0];
                $('#kategori_status_komunikasi_<?= $fol_up_sekarang ?>').val(data.kategori);
              });
            </script>


            <div class="form-group">
              <label class="col-sm-4 control-label">Kategori Status Komunikasi</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control" id='kategori_status_komunikasi_<?= $fol_up_sekarang ?>' disabled value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['kategori_status_komunikasi'] : '' ?>' <?= $disabled ?>>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">Hasil Status Follow Up</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <select style="width:100%" id="kodeHasilStatusFollowUp_<?= $fol_up_sekarang ?>" class='form-control' name='kodeHasilStatusFollowUp_<?= $fol_up_sekarang ?>' <?= $disabled ?>>
                    <?php if (isset($list_follow_up[$fol_up_sekarang])) {
                      $lfu = $list_follow_up[$fol_up_sekarang]; ?>
                      <option value='<?= $lfu['kodeHasilStatusFollowUp'] ?>'><?= $lfu['deskripsiHasilStatusFollowUp'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group" id="input_kodeAlasanNotProspectNotDeal_<?= $fol_up_sekarang ?>">
              <label class="col-sm-4 control-label">Alasan Follow Up <?= $fol_up_sekarang ?> Not Prospect/Not Deal</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <select style="width:100%" id="kodeAlasanNotProspectNotDeal_<?= $fol_up_sekarang ?>" class='form-control' name='kodeAlasanNotProspectNotDeal_<?= $fol_up_sekarang ?>' <?= $disabled ?>>
                    <?php if (isset($list_follow_up[$fol_up_sekarang])) {
                      $lfu = $list_follow_up[$fol_up_sekarang]; ?>
                      <option value='<?= $lfu['kodeAlasanNotProspectNotDeal'] ?>'><?= $lfu['alasanNotProspectNotDeal'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <script>
              // $(document).ready(function() {
              //   var selected_hasil<?= $fol_up_sekarang ?> = $('#kodeHasilStatusFollowUp_<?= $fol_up_sekarang ?>').select2().val();
              //   if (selected_hasil<?= $fol_up_sekarang ?> == 2 || selected_hasil<?= $fol_up_sekarang ?> == 4) {
              //     $("#")
              //   }
              // });
            </script>

            <div class="form-group">
              <label class="col-sm-4 control-label">Keterangan Alasan Lainnya</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control" name='keteranganAlasanLainnya_<?= $fol_up_sekarang ?>' required value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['keteranganAlasanLainnya'] : '' ?>' <?= $disabled ?>>
                </div>
              </div>
            </div>
          </div>
        <?php $fol_up_sekarang++;
        } ?>
      </div>
      <div class="form-group" style='padding-top:20px'>
        <div class="col-sm-6">
          <button type="button" id="backTo_<?= $back ?>" class="btn btn-primary btn-flat" onclick="saveDataFollowUp(this,'<?= $back ?>','back',<?= $i ?>)"><i class="fa fa-backward"></i> Halaman Sebelumnya</button>
        </div>
        <div class="col-sm-6" align="right">
          <?php if ($i < $tot_tab_fol) { ?>
            <button onclick="saveDataFollowUp(this,'data_follow_up_<?= $i + 1 ?>','next',<?= $i ?>)" type="button" id="#backTo_data_pendukung_probing_1" class="btn btn-primary btn-flat"><i class="fa fa-forward"></i> Halaman Berikutnya</button>
          <?php } else { ?>
            <?php if ($disabled == '') { ?>
              <button onclick="tambahDataFollowUp(this,<?= count($list_follow_up) + 1 ?>,<?= $i ?>)" type="button" id="#nextTo_data_follow_up_<?= $i + 1 ?>" class="btn btn-info btn-flat">Tambah Follow Up <?= count($list_follow_up) + 1 ?></button>
              <button onclick="saveDataFollowUp(this,'data_follow_up_<?= $i ?>',1,<?= $i ?>)" type="button" class="btn bg-blue btn-flat">Simpan Follow Up</button>
              <button onclick="saveCheckDataAndSendAPI3(this,<?= $i ?>)" type="button" class="btn bg-green btn-flat"><i class='fa fa-save'></i> Simpan Data</button>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    </form>
  </div>
<?php } ?>
<?php
$data['data'] = ['selectMediaKomunikasiFolupMulti', 'selectStatusKomunikasiFolUpMulti', 'selectKategoriStatusKomunikasiMulti', 'selectHasilStatusFollowUpMulti', 'selectAlasanNotProspectNotDealMulti'];
$data['total_fol_up'] = $total_fol_up;
$this->load->view('additionals/dropdown_search_menu_leads_customer_data', $data); ?>
<script>
  function saveDataFollowUp(el, tabs, position, fu) {
    if (position == 'back') {
      var default_name_button = '<i class = "fa fa-backward"></i> Halaman Sebelumnya';
    } else if (position == 'back') {
      var default_name_button = '<i class = "fa fa-forward"></i> Halaman Berikutnya';
    } else {
      var default_name_button = 'Simpan Follow Up';

    }
    var val_form_follow_up = new FormData($('#form_data_follow_up_' + fu)[0]);
    val_form_follow_up.append('leads_id', '<?= $row->leads_id ?>');

    <?php if ($disabled == 'disabled') { ?>
      changeTabs(tabs);
      return false;
    <?php } ?>
    $.ajax({
      beforeSend: function() {
        $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
        $(el).attr('disabled', true);
      },
      enctype: 'multipart/form-data',
      url: '<?= site_url(get_controller() . '/saveEditFollowUp') ?>',
      type: "POST",
      data: val_form_follow_up,
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
  }

  function tambahDataFollowUp(el, fu, tabs_no) {
    Swal.fire({
      title: 'Apakah Anda Yakin Menambah Follow Up Baru ?',
      showCancelButton: true,
      confirmButtonText: 'Ya',
      cancelButtonText: 'Batal',
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        values = {
          fol: fu,
          leads_id: "<?= $row->leads_id ?>",
          tabs: 'data_follow_up_' + tabs_no
        }
        $.ajax({
          beforeSend: function() {
            $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
            $(el).attr('disabled', true);
          },
          enctype: 'multipart/form-data',
          url: '<?= site_url(get_controller() . '/tambahDataFollowUp') ?>',
          type: "POST",
          data: values,
          // processData: false,
          // contentType: false,
          cache: false,
          dataType: 'JSON',
          success: function(response) {
            if (response.status == 1) {
              location.reload(true);
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
              $(el).attr('disabled', false);
            }
            $(el).html('Tambah Folllow Up ' + fu);
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
            $(el).html('Tambah Folllow Up ' + fu);
            $(el).attr('disabled', false);
          }
        });
      } else if (result.isDenied) {
        // Swal.fire('Changes are not saved', '', 'info')
      }
    })
  }

  function saveCheckDataAndSendAPI3(el, fu) {
    $('.form_').validate({
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

    if ($('.form_').valid()) // check if form is valid
    {
      console.log('oke');
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

    var default_name_button = "<i class='fa fa-save'></i> Simpan Data";
    var val_form_follow_up = new FormData($('#form_data_follow_up_' + fu)[0]);
    val_form_follow_up.append('leads_id', '<?= $row->leads_id ?>');
    val_form_follow_up.append('is_simpan', true);

    $.ajax({
      beforeSend: function() {
        $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
        $(el).attr('disabled', true);
      },
      enctype: 'multipart/form-data',
      url: '<?= site_url(get_controller() . '/saveEditFollowUp') ?>',
      type: "POST",
      data: val_form_follow_up,
      processData: false,
      contentType: false,
      // cache: false,
      dataType: 'JSON',
      success: function(response) {
        if (response.status == 1) {
          Swal.fire({
            icon: 'success',
            title: '<font color="black">Informasi</font>',
            html: '<font color="black">' + response.pesan + '</font>',
            confirmButtonText: 'Tutup',
          })
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
  }
</script>